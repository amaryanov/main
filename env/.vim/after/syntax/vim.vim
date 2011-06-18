" indenthl.vim: hilights each indent level in different colors.
" Author: Dane Summers
" Date: Feb 15, 2007
" Version: 2
" 
" See :help mysyntaxfile-add for how to install this file.

syn match cTab1 /^\(  \)/
syn match cTab2 /\(^\(  \)\)\@<=\(  \)/
syn match cTab3 /\(^\(  \)\{2}\)\@<=\(  \)/
syn match cTab4 /\(^\(  \)\{3}\)\@<=\(  \)/
syn match cTab5 /\(^\(  \)\{4}\)\@<=\(  \)/
syn match cTab6 /\(^\(  \)\{5}\)\@<=\(  \)/
syn match cTab7 /\(^\(  \)\{6}\)\@<=\(  \)/
syn match cTab8 /\(^\(  \)\{7}\)\@<=\(  \)/
syn match cTab9 /\(^\(  \)\{8}\)\@<=\(  \)/
syn match cTab10 /\(^\(  \)\{9}\)\@<=\(  \)/
syn match cTab11 /\(^\(  \)\{10}\)\@<=\(  \)/
syn match spaceStart /^ *\t\+/
syn match spaceEnd /\s\+$/

let g:TabsHl = 0
function! s:MyHl()
  if g:TabsHl == 1
    let g:TabsHl = 0
    hi clear cTab1
    hi clear cTab2
    hi clear cTab3
    hi clear cTab4
    hi clear cTab5
    hi clear cTab6
    hi clear cTab7
    hi clear cTab8
    hi clear cTab9
    hi clear cTab10
    hi clear cTab11
    hi clear spaceStart
    hi clear spaceEnd
  else
    let g:TabsHl = 1
    command! -nargs=+ HiLink hi def <args>
    " to make colors slightly darker at each level (in gui)
    HiLink cTab1 term=NONE cterm=NONE ctermbg=255 gui=NONE guibg=gray90
    HiLink cTab2 term=NONE cterm=NONE ctermbg=254 gui=NONE guibg=gray85
    HiLink cTab3 term=NONE cterm=NONE ctermbg=253 gui=NONE guibg=gray80
    HiLink cTab4 term=NONE cterm=NONE ctermbg=252 gui=NONE guibg=gray75
    HiLink cTab5 term=NONE cterm=NONE ctermbg=251 gui=NONE guibg=gray70
    HiLink cTab6 term=NONE cterm=NONE ctermbg=250 gui=NONE guibg=gray65
    HiLink cTab7 term=NONE cterm=NONE ctermbg=249 gui=NONE guibg=gray60
    HiLink cTab8 term=NONE cterm=NONE ctermbg=248 gui=NONE guibg=gray60
    HiLink cTab9 term=NONE cterm=NONE ctermbg=247 gui=NONE guibg=gray60
    HiLink cTab10 term=NONE cterm=NONE ctermbg=246 gui=NONE guibg=gray60
    HiLink cTab10 term=NONE cterm=NONE ctermbg=245 gui=NONE guibg=gray60
    HiLink spaceStart term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
    HiLink spaceEnd term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
    delcommand HiLink
  endif
endfunction
nmap <F9> :call <SID>MyHl()<CR>
call s:MyHl()
