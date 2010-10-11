#!/usr/bin/perl

my $mysql = "/home/www/server/build/mysql/bin/mysql";
my $mysqlconf = "/home/www/server/data/mysql/conf/client.cnf";
my $db = "cifrograd_mailer";
my $tmp_mysql_log = "/tmp/message-mysql-XXXX";


my $message = do {local (@ARGV,$/); <STDIN>};
%exit_codes = ("OK", 0, "DATAERR", 65, "TERM", 1);
%db_statuses = ("DELIVERED", "2", "UNDELIVERED", "3");
%statuses = ("failed", $db_statuses{"UNDELIVERED"},
	"delayed", $db_statuses{"UNDELIVERED"},
	"delivered", $db_statuses{"DELIVERED"},
	"relayed", $db_statuses{"DELIVERED"}, 
	"expanded", $db_statuses{"DELIVERED"});


sub getHeader
{
	my $message = $_[0];
	my $header_name = $_[1];
	my $header_partnum = $_[2];
	$message =~ /^\Q${header_name}\E:[ \t]*([^\n]+\n([\t ]+[^\n]+\n)*)/msi;
	my $header = $1;
	$header =~ s/\n\s*//ms;
	if ( length($header_partnum) > 0 )
	{
		if ( $header_partnum =~ /^\d+$/ )
		{
			my @header_parts = split(/\s*;\s*/, $header);
			$header = $header_parts[$header_partnum];
		}
		else
		{
			$header =~ /(^|;)\s*\Q${header_partnum}\E=((\x22|\x27)?)([^;]+)(\2)/;
			$header = $4;
		}
	}
	trim($header);
	return $header;
}

sub trim
{
	$_[0] =~ s/(\A\s+)|(\s+\z)//g;
	return $_[0];
}

sub splitMessage
{
	my $message = $_[0];
	my $boundary = getHeader($message, "content-type", "boundary");
	$message =~ s/--\Q$boundary\E--//;
	my @parts = ();
	foreach $part (split(/--\Q$boundary\E/ms, $message))
	{
		push @parts, trim($part);
	}
	return @parts;
}

sub findByContentType
{
	my @message_parts = @{@_[0]};
	my $contentType = $_[1];
	my $count = @message_parts;
	for($i = 0; $i < $count; $i++)
	{
		my $header = getHeader($message_parts[$i], 'content-type');
		if(getHeader($message_parts[$i], 'content-type') eq $contentType)
		{
			return $i;
		}
	}
	return -1;
}

sub myexit
{
	print $_[1]."\n";
	exit $_[0];
}

sub getReports
{
	my $dsn_part = $_[0];
	my @subparts = ();
	foreach $subpart (split("\n\n", $dsn_part))
	{
		if ( getHeader($subpart, "final-recipient") ne "" )
		{
			push @subparts, $subpart;
		}
	}
	return @subparts;
}
sub mysql_escape
{
	#\, ', "
	$_[0] =~ s/([\\\x22\x27])/\\\1/g;
	return $_[0];
}

sub updateStatus
{
	my ($recipient, $list_id, $status, $auth_hash, $report) = @_;
	$report = mysql_escape($report);
	my $set_auth  = "";
	if ( $db_statuses{"DELIVERED"} == $status )
	{
		$set_auth = "s.auth_hash='$auth_hash',";
	}
	my $query = "
		update
			subscriptions s,
			users u
		set
			s.status='$status',
			$set_auth
			s.report='$report'
		where
			u.email='$recipient'
			and s.user_id=u.id
			and s.maillist_id='$list_id'";
	my $tempfile = trim(`mktemp $tmp_mysql_log`);
	my $cmd = "$mysql --defaults-file=$mysqlconf -D $db -e \"$query\"";
	if ( length($tempfile) > 0 )
	{
		$cmd .= " > $tempfile 2>&1";
	}
	system($cmd);
	if ( $? != 0 )
	{
		local $/=undef;
		my $string = "";
		if ( open FILE, $tempfile)
		{
			binmode FILE;
			$string = <FILE>;
			close FILE;
		}
		else
		{
			$string = `cat $tempfile`;
		}
		print "Cant set status: $cmd \n $string";
	}
	elsif ( length($tempfile) > 0 )
	{
		unlink($tempfile);
	}
}

my @message_parts = splitMessage($message);

$report_type = getHeader($message, "content-type", "report-type");
if ( $report_type ne "delivery-status" )
{
	myexit($exit_codes{"DATAERR"}, "Looks like it does not have delivery status report.");
}

my $dsnPart = findByContentType(\@message_parts, "message/delivery-status");
if ( $dsnPart == -1 )
{
	myexit($exit_codes{"DATAERR"}, "Cant find delivery status message part.");
}

my $messagePart = findByContentType(\@message_parts, "message/rfc822");
if ( $messagePart == -1 )
{
	$messagePart = findByContentType(\@message_parts, "text/rfc822-headers");
}
if ( $messagePart == -1 )
{
	myexit($exit_codes{"DATAERR"}, "Cant find source message or source message headers.");
}

my $unsubscribeLink = getHeader($message_parts[$messagePart], "list-unsubscribe");
$unsubscribeLink =~ /unsubscribe\/([a-z0-9]{64})(\d+)/;
my $unsubscribeHash = $1;
my $list_id = $2;
if ( $list_id !~ /^\d+$/ )
{
	myexit($exit_codes{"DATAERR"}, "Cant find list id.");
}
if ( $unsubscribeHash !~ /^[a-z0-9]+$/ )
{
	myexit($exit_codes{"DATAERR"}, "Cant find unsubscribe hash.");
}

my @reports = getReports($message_parts[$dsnPart]);
if ( @reports == 0)
{
	myexit($exit_codes{"DATAERR"}, "Cant find reports.");
}

foreach $report (@reports)
{
	my $action = getHeader($report, "action");
	if ( $action eq "" )
	{
		print "Cant find action in report:" . $report."\n";
		next;
	}
	my $db_action = $statuses{$action};
	if ( $db_action eq "" )
	{
		print "Unknown action in report:" . $report."\n";
		next;
	}
	my $recipient = getHeader($report, "final-recipient", "1");
	if ( $recipient eq "" )
	{
		print "Cant find recipient in report:" . $report."\n";
		next;
	}
	updateStatus($recipient, $list_id, $db_action, $unsubscribeHash, $report);
}

myexit($exit_codes{"OK"}, "Done successfully");
