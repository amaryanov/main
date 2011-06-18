syn match cTab1 /^\t/ containedin=ALL
syn match cTab2 /\(^\t\)\@<=\t/ containedin=ALL
syn match cTab3 /\(^\t\{2}\)\@<=\t/ containedin=ALL
syn match cTab4 /\(^\t\{3}\)\@<=\t/ containedin=ALL
syn match cTab5 /\(^\t\{4}\)\@<=\t/ containedin=ALL
syn match cTab6 /\(^\t\{5}\)\@<=\t/ containedin=ALL
syn match cTab7 /\(^\t\{6}\)\@<=\t/ containedin=ALL
syn match cTab8 /\(^\t\{7}\)\@<=\t/ containedin=ALL
syn match cTab9 /\(^\t\{8}\)\@<=\t/ containedin=ALL
syn match cTab10 /\(^\t\{9}\)\@<=\t/ containedin=ALL
syn match cTab11 /\(^\t\{10}\)\@<=\t/ containedin=ALL
syn match cTab12 /\(^\t\{11}\)\@<=\t/ containedin=ALL
syn match cTab13 /\(^\t\{12}\)\@<=\t/ containedin=ALL
syn match cTab14 /\(^\t\{13}\)\@<=\t/ containedin=ALL
syn match cTab15 /\(^\t\{14}\)\@<=\t/ containedin=ALL
syn match cTab16 /\(^\t\{15}\)\@<=\t/ containedin=ALL
syn match cTab17 /\(^\t\{16}\)\@<=\t/ containedin=ALL
syn match cTab18 /\(^\t\{17}\)\@<=\t/ containedin=ALL
syn match cTab19 /\(^\t\{18}\)\@<=\t/ containedin=ALL
syn match cTab20 /\(^\t\{19}\)\@<=\t/ containedin=ALL
syn match cTab21 /\(^\t\{20}\)\@<=\t/ containedin=ALL
syn match cTab22 /\(^\t\{21}\)\@<=\t/ containedin=ALL
syn match cTab23 /\(^\t\{22}\)\@<=\t/ containedin=ALL
syn match cTab24 /\(^\t\{23}\)\@<=\t/ containedin=ALL
syn match spaceStart /^\t* \+/ containedin=ALL
syn match spaceEnd /\s\+$/ containedin=ALL
syn match EmptyLine /^\(\t\+ *\)\|\(\t* \+\)$/ containedin=ALL

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
    hi clear cTab12
    hi clear cTab13
    hi clear cTab14
    hi clear cTab15
    hi clear cTab16
    hi clear cTab17
    hi clear cTab18
    hi clear cTab19
    hi clear cTab20
    hi clear cTab21
    hi clear cTab22
    hi clear cTab23
    hi clear cTab24
    hi clear spaceStart
    hi clear spaceEnd
    hi clear EmptyLine
  else
    let g:TabsHl = 1
    command! -nargs=+ HiLink hi def <args>

    HiLink cTab1 term=NONE cterm=NONE ctermbg=254 gui=NONE guibg=gray90
    HiLink cTab2 term=NONE cterm=NONE ctermbg=253 gui=NONE guibg=gray85
    HiLink cTab3 term=NONE cterm=NONE ctermbg=252 gui=NONE guibg=gray80
    HiLink cTab4 term=NONE cterm=NONE ctermbg=251 gui=NONE guibg=gray75
    HiLink cTab5 term=NONE cterm=NONE ctermbg=250 gui=NONE guibg=gray70
    HiLink cTab6 term=NONE cterm=NONE ctermbg=249 gui=NONE guibg=gray65
    HiLink cTab7 term=NONE cterm=NONE ctermbg=248 gui=NONE guibg=gray60
    HiLink cTab8 term=NONE cterm=NONE ctermbg=247 gui=NONE guibg=gray55
    HiLink cTab9 term=NONE cterm=NONE ctermbg=246 gui=NONE guibg=gray50
    HiLink cTab10 term=NONE cterm=NONE ctermbg=245 gui=NONE guibg=gray45
    HiLink cTab11 term=NONE cterm=NONE ctermbg=244 gui=NONE guibg=gray45
    HiLink cTab12 term=NONE cterm=NONE ctermbg=243 gui=NONE guibg=gray45
    HiLink cTab13 term=NONE cterm=NONE ctermbg=242 gui=NONE guibg=gray45
    HiLink cTab14 term=NONE cterm=NONE ctermbg=241 gui=NONE guibg=gray45
    HiLink cTab15 term=NONE cterm=NONE ctermbg=240 gui=NONE guibg=gray45
    HiLink cTab16 term=NONE cterm=NONE ctermbg=239 gui=NONE guibg=gray45
    HiLink cTab17 term=NONE cterm=NONE ctermbg=238 gui=NONE guibg=gray45
    HiLink cTab18 term=NONE cterm=NONE ctermbg=237 gui=NONE guibg=gray45
    HiLink cTab19 term=NONE cterm=NONE ctermbg=236 gui=NONE guibg=gray45
    HiLink cTab20 term=NONE cterm=NONE ctermbg=235 gui=NONE guibg=gray45
    HiLink cTab21 term=NONE cterm=NONE ctermbg=234 gui=NONE guibg=gray45
    HiLink cTab22 term=NONE cterm=NONE ctermbg=233 gui=NONE guibg=gray45
    HiLink cTab23 term=NONE cterm=NONE ctermbg=232 gui=NONE guibg=gray45
    HiLink cTab24 term=NONE cterm=NONE ctermbg=231 gui=NONE guibg=gray45

    HiLink spaceStart term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
    HiLink spaceEnd term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
    HiLink EmptyLine term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
	delcommand HiLink
  endif
endfunction
nmap <F9> :call <SID>MyHl()<CR>
if !exists("g:TabsHl")
  let g:TabsHl = 0
  call s:MyHl()
endif

