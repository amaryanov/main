function! RunPhpdoc()
    let l:filename=@%
    let l:phpcs_output=system('cat '.l:filename.' | /home/amaryanov/pear/bin/docblockgen -c PHP')
	:1,$d
	put! =l:phpcs_output
endfunction

command! Phpdoc execute RunPhpdoc()
