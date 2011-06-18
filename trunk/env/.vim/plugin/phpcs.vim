function! RunPhpcs()
    let l:filename=@%
    let l:phpcs_output=system('/home/amaryanov/pear/bin/phpcs --report=csv --standard=PEAR '.l:filename)
"    echo l:phpcs_output
    let l:phpcs_list=split(l:phpcs_output, "\n")
    unlet l:phpcs_list[0]
    cexpr l:phpcs_list
    cwindow
endfunction

set errorformat+="%f:%l:%c:%t%*[a-zA-Z]:%m"
""set errorformat+=\"%f\"\\,%l\\,%c\\,%t%*[a-zA-Z]\\,\"%m\"
"set errorformat+=%m\ in\ %f\ on\ line\ %l
command! Phpcs execute RunPhpcs()
