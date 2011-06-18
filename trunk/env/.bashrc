export LANG=en_US.UTF-8
# detect number of supported colors
SCREEN_COLORS="`tput colors`"
if [ -z "$SCREEN_COLORS" ] ; then
    case "$TERM" in
        screen-*color-bce)
            echo "Unknown terminal $TERM. Falling back to 'screen-bce'."
            export TERM=screen-bce
            ;;
        *-88color)
            echo "Unknown terminal $TERM. Falling back to 'xterm-88color'."
            export TERM=xterm-88color
            ;;
        *-256color)
            echo "Unknown terminal $TERM. Falling back to 'xterm-256color'."
            export TERM=xterm-256color
            ;;
    esac
    SCREEN_COLORS=`tput colors`
fi
if [ -z "$SCREEN_COLORS" ] ; then
    case "$TERM" in
        gnome*|xterm*|konsole*|aterm|[Ee]term)
            echo "Unknown terminal $TERM. Falling back to 'xterm'."
            export TERM=xterm
            ;;
        rxvt*)
            echo "Unknown terminal $TERM. Falling back to 'rxvt'."
            export TERM=rxvt
            ;;
        screen*)
            echo "Unknown terminal $TERM. Falling back to 'screen'."
            export TERM=screen
            ;;
    esac
    SCREEN_COLORS=`tput colors`
fi

test -s ~/.alias && . ~/.alias || true

function jobcount
{
        stopped="$(jobs -s | wc -l | tr -d " ")"
        running="$(jobs -r | wc -l | tr -d " ")"
        echo -n "${running}/${stopped}"
}

if [[ $USER == "amaryanov" ]]
then
	PS1='\[\033[2;39m\]\u\[\033[2;31m\]@\h\[\033[0m\] \[\033[1;34m\]$(pwd)/ \[\033[0m\][$(jobcount)] \[\033[0;32m\]$ \[\033[0m\]';
else
	PS1='\[\033[2;31m\]\u@\h\[\033[0m\] \[\033[1;34m\]$(pwd)/ \[\033[0m\][$(jobcount)] \[\033[0;31m\]# \[\033[0m\]';
fi

export SVN_EDITOR='vim'
