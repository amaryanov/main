" This scheme was created by CSApproxSnapshot
" on Чтв, 22 Окт 2009

hi clear
if exists("syntax_on")
    syntax reset
endif

if v:version < 700
    let g:colors_name = expand("<sfile>:t:r")
    command! -nargs=+ CSAHi exe "hi" substitute(substitute(<q-args>, "undercurl", "underline", "g"), "guisp\\S\\+", "", "g")
else
    let g:colors_name = expand("<sfile>:t:r")
    command! -nargs=+ CSAHi exe "hi" <q-args>
endif

if 0
elseif has("gui_running") || (&t_Co == 256 && (&term ==# "xterm" || &term =~# "^screen") && exists("g:CSApprox_konsole") && g:CSApprox_konsole) || &term =~? "^konsole"
    CSAHi Normal term=NONE cterm=NONE ctermbg=231 ctermfg=16 gui=NONE guibg=#ffffff guifg=#000000
    CSAHi Underlined term=underline cterm=NONE ctermbg=231 ctermfg=21 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi Ignore term=NONE cterm=NONE ctermbg=231 ctermfg=231 gui=NONE guibg=#ffffff guifg=#ffffff
    CSAHi Error term=reverse cterm=NONE ctermbg=63 ctermfg=231 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi Todo term=NONE cterm=NONE ctermbg=231 ctermfg=210 gui=NONE guibg=#ffffff guifg=#ff5050
    CSAHi String term=NONE cterm=NONE ctermbg=231 ctermfg=127 gui=NONE guibg=#ffffff guifg=#8010a0
    CSAHi Number term=NONE cterm=NONE ctermbg=231 ctermfg=21 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi SpecialKey term=bold cterm=NONE ctermbg=231 ctermfg=250 gui=NONE guibg=#ffffff guifg=#c0c0c0
    CSAHi NonText term=bold cterm=NONE ctermbg=254 ctermfg=242 gui=NONE guibg=#e7e7e7 guifg=#707070
    CSAHi Directory term=bold cterm=NONE ctermbg=231 ctermfg=105 gui=NONE guibg=#ffffff guifg=#7050ff
    CSAHi ErrorMsg term=NONE cterm=NONE ctermbg=63 ctermfg=231 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi IncSearch term=reverse cterm=underline ctermbg=185 ctermfg=238 gui=underline guibg=#e0e040 guifg=#404040
    CSAHi Search term=reverse cterm=NONE ctermbg=216 ctermfg=black gui=NONE guibg=#f0c0ff guifg=#544060
    CSAHi MoreMsg term=bold cterm=NONE ctermbg=231 ctermfg=37 gui=NONE guibg=#ffffff guifg=#0090a0
    CSAHi ModeMsg term=bold cterm=NONE ctermbg=231 ctermfg=172 gui=NONE guibg=#ffffff guifg=#d06000
    CSAHi LineNr term=underline cterm=NONE ctermbg=255 ctermfg=242 gui=NONE guibg=#eeeeee guifg=#6b6b6b
    CSAHi Pmenu term=NONE cterm=NONE ctermbg=225 ctermfg=fg gui=NONE guibg=#ffbbff guifg=fg
    CSAHi PmenuSel term=NONE cterm=NONE ctermbg=250 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuSbar term=NONE cterm=NONE ctermbg=250 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuThumb term=NONE cterm=NONE ctermbg=16 ctermfg=231 gui=reverse guibg=bg guifg=fg
    CSAHi TabLine term=underline cterm=underline ctermbg=252 ctermfg=fg gui=underline guibg=#d3d3d3 guifg=fg
    CSAHi TabLineSel term=bold cterm=bold ctermbg=bg ctermfg=fg gui=bold guibg=bg guifg=fg
    CSAHi TabLineFill term=reverse cterm=NONE ctermbg=16 ctermfg=231 gui=reverse guibg=bg guifg=fg
    CSAHi CursorColumn term=reverse cterm=NONE ctermbg=254 ctermfg=fg gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi CursorLine term=underline cterm=NONE ctermbg=230 ctermfg=NONE gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi Cursor term=NONE cterm=NONE ctermbg=39 ctermfg=231 gui=NONE guibg=#0080f0 guifg=#ffffff
    CSAHi CursorIM term=NONE cterm=NONE ctermbg=135 ctermfg=231 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi Question term=NONE cterm=NONE ctermbg=231 ctermfg=129 gui=NONE guibg=#ffffff guifg=#8000ff
    CSAHi StatusLine term=reverse,bold cterm=NONE ctermbg=67 ctermfg=231 gui=NONE guibg=#4570aa guifg=#ffffff
    CSAHi StatusLineNC term=reverse cterm=NONE ctermbg=110 ctermfg=231 gui=NONE guibg=#75a0da guifg=#ffffff
    CSAHi VertSplit term=reverse cterm=NONE ctermbg=131 ctermfg=231 gui=NONE guibg=#904838 guifg=#f8f8f8
    CSAHi Title term=bold cterm=bold ctermbg=231 ctermfg=26 gui=bold guibg=#ffffff guifg=#0033cc
    CSAHi Visual term=reverse cterm=NONE ctermbg=223 ctermfg=131 gui=NONE guibg=#ffc0a0 guifg=#804020
    CSAHi VisualNOS term=bold,underline cterm=bold,underline ctermbg=bg ctermfg=fg gui=bold,underline guibg=bg guifg=fg
    CSAHi WarningMsg term=NONE cterm=NONE ctermbg=63 ctermfg=231 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi WildMenu term=NONE cterm=NONE ctermbg=203 ctermfg=231 gui=NONE guibg=#ff3030 guifg=#f8f8f8
    CSAHi Folded term=NONE cterm=NONE ctermbg=230 ctermfg=131 gui=NONE guibg=#fff0d0 guifg=#804030
    CSAHi lCursor term=NONE cterm=NONE ctermbg=135 ctermfg=231 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi MatchParen term=reverse cterm=NONE ctermbg=51 ctermfg=fg gui=NONE guibg=#00ffff guifg=fg
    CSAHi Comment term=bold cterm=NONE ctermbg=231 ctermfg=65 gui=NONE guibg=#ffffff guifg=#236e25
    CSAHi Constant term=underline cterm=NONE ctermbg=231 ctermfg=35 gui=NONE guibg=#ffffff guifg=#00884c
    CSAHi Special term=bold cterm=NONE ctermbg=231 ctermfg=135 gui=NONE guibg=#ffffff guifg=#8040f0
    CSAHi Identifier term=underline cterm=NONE ctermbg=231 ctermfg=136 gui=NONE guibg=#ffffff guifg=#b07800
    CSAHi Statement term=bold cterm=NONE ctermbg=231 ctermfg=175 gui=NONE guibg=#ffffff guifg=#b64f90
    CSAHi PreProc term=underline cterm=NONE ctermbg=231 ctermfg=95 gui=NONE guibg=#ffffff guifg=#683821
    CSAHi Type term=underline cterm=bold ctermbg=231 ctermfg=90 gui=bold guibg=#ffffff guifg=#7f0055
    CSAHi FoldColumn term=NONE cterm=NONE ctermbg=254 ctermfg=242 gui=NONE guibg=#e7e7e7 guifg=#6b6b6b
    CSAHi DiffAdd term=bold cterm=NONE ctermbg=231 ctermfg=21 gui=NONE guibg=#e7e7ff guifg=#0000ff
    CSAHi DiffChange term=bold cterm=NONE ctermbg=231 ctermfg=16 gui=NONE guibg=#ffe7e7 guifg=#000000
    CSAHi DiffDelete term=bold cterm=NONE ctermbg=231 ctermfg=231 gui=NONE guibg=#e7e7ff guifg=#ffffff
    CSAHi DiffText term=reverse cterm=NONE ctermbg=224 ctermfg=196 gui=NONE guibg=#ffd0d0 guifg=#ff0000
    CSAHi SignColumn term=NONE cterm=NONE ctermbg=250 ctermfg=19 gui=NONE guibg=#bebebe guifg=#00008b
    CSAHi SpellBad term=reverse cterm=undercurl ctermbg=bg ctermfg=196 gui=undercurl guibg=bg guifg=fg guisp=Red
    CSAHi SpellCap term=reverse cterm=undercurl ctermbg=bg ctermfg=21 gui=undercurl guibg=bg guifg=fg guisp=Blue
    CSAHi SpellRare term=reverse cterm=undercurl ctermbg=bg ctermfg=201 gui=undercurl guibg=bg guifg=fg guisp=Magenta
    CSAHi SpellLocal term=underline cterm=undercurl ctermbg=bg ctermfg=37 gui=undercurl guibg=bg guifg=fg guisp=DarkCyan
elseif has("gui_running") || (&t_Co == 256 && (&term ==# "xterm" || &term =~# "^screen") && exists("g:CSApprox_eterm") && g:CSApprox_eterm) || &term =~? "^eterm"
    CSAHi Normal term=NONE cterm=NONE ctermbg=255 ctermfg=16 gui=NONE guibg=#ffffff guifg=#000000
    CSAHi Underlined term=underline cterm=NONE ctermbg=255 ctermfg=21 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi Ignore term=NONE cterm=NONE ctermbg=255 ctermfg=255 gui=NONE guibg=#ffffff guifg=#ffffff
    CSAHi Error term=reverse cterm=NONE ctermbg=105 ctermfg=255 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi Todo term=NONE cterm=NONE ctermbg=160 ctermfg=255 gui=NONE guibg=#ffffff guifg=#ff5050
    CSAHi String term=NONE cterm=NONE ctermbg=255 ctermfg=128 gui=NONE guibg=#ffffff guifg=#8010a0
    CSAHi Number term=NONE cterm=NONE ctermbg=255 ctermfg=21 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi SpecialKey term=bold cterm=NONE ctermbg=255 ctermfg=250 gui=NONE guibg=#ffffff guifg=#c0c0c0
    CSAHi NonText term=bold cterm=NONE ctermbg=254 ctermfg=242 gui=NONE guibg=#e7e7e7 guifg=#707070
    CSAHi Directory term=bold cterm=NONE ctermbg=255 ctermfg=141 gui=NONE guibg=#ffffff guifg=#7050ff
    CSAHi ErrorMsg term=NONE cterm=NONE ctermbg=105 ctermfg=255 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi IncSearch term=reverse cterm=underline ctermbg=228 ctermfg=238 gui=underline guibg=#e0e040 guifg=#404040
    CSAHi Search term=reverse cterm=NONE ctermbg=216 ctermfg=black gui=NONE guibg=#f0c0ff guifg=#544060
    CSAHi MoreMsg term=bold cterm=NONE ctermbg=255 ctermfg=38 gui=NONE guibg=#ffffff guifg=#0090a0
    CSAHi ModeMsg term=bold cterm=NONE ctermbg=255 ctermfg=208 gui=NONE guibg=#ffffff guifg=#d06000
    CSAHi LineNr term=underline cterm=NONE ctermbg=255 ctermfg=242 gui=NONE guibg=#eeeeee guifg=#6b6b6b
    CSAHi Pmenu term=NONE cterm=NONE ctermbg=225 ctermfg=fg gui=NONE guibg=#ffbbff guifg=fg
    CSAHi PmenuSel term=NONE cterm=NONE ctermbg=250 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuSbar term=NONE cterm=NONE ctermbg=250 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuThumb term=NONE cterm=NONE ctermbg=16 ctermfg=255 gui=reverse guibg=bg guifg=fg
    CSAHi TabLine term=underline cterm=underline ctermbg=231 ctermfg=fg gui=underline guibg=#d3d3d3 guifg=fg
    CSAHi TabLineSel term=bold cterm=bold ctermbg=bg ctermfg=fg gui=bold guibg=bg guifg=fg
    CSAHi TabLineFill term=reverse cterm=NONE ctermbg=16 ctermfg=255 gui=reverse guibg=bg guifg=fg
    CSAHi CursorColumn term=reverse cterm=NONE ctermbg=254 ctermfg=fg gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi CursorLine term=underline cterm=NONE ctermbg=230 ctermfg=NONE gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi Cursor term=NONE cterm=NONE ctermbg=39 ctermfg=255 gui=NONE guibg=#0080f0 guifg=#ffffff
    CSAHi CursorIM term=NONE cterm=NONE ctermbg=141 ctermfg=255 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi Question term=NONE cterm=NONE ctermbg=255 ctermfg=129 gui=NONE guibg=#ffffff guifg=#8000ff
    CSAHi StatusLine term=reverse,bold cterm=NONE ctermbg=110 ctermfg=255 gui=NONE guibg=#4570aa guifg=#ffffff
    CSAHi StatusLineNC term=reverse cterm=NONE ctermbg=153 ctermfg=255 gui=NONE guibg=#75a0da guifg=#ffffff
    CSAHi VertSplit term=reverse cterm=NONE ctermbg=137 ctermfg=255 gui=NONE guibg=#904838 guifg=#f8f8f8
    CSAHi Title term=bold cterm=bold ctermbg=255 ctermfg=27 gui=bold guibg=#ffffff guifg=#0033cc
    CSAHi Visual term=reverse cterm=NONE ctermbg=230 ctermfg=137 gui=NONE guibg=#ffc0a0 guifg=#804020
    CSAHi VisualNOS term=bold,underline cterm=bold,underline ctermbg=bg ctermfg=fg gui=bold,underline guibg=bg guifg=fg
    CSAHi WarningMsg term=NONE cterm=NONE ctermbg=105 ctermfg=255 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi WildMenu term=NONE cterm=NONE ctermbg=203 ctermfg=255 gui=NONE guibg=#ff3030 guifg=#f8f8f8
    CSAHi Folded term=NONE cterm=NONE ctermbg=231 ctermfg=137 gui=NONE guibg=#fff0d0 guifg=#804030
    CSAHi lCursor term=NONE cterm=NONE ctermbg=141 ctermfg=255 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi MatchParen term=reverse cterm=NONE ctermbg=51 ctermfg=fg gui=NONE guibg=#00ffff guifg=fg
    CSAHi Comment term=bold cterm=NONE ctermbg=255 ctermfg=71 gui=NONE guibg=#ffffff guifg=#236e25
    CSAHi Constant term=underline cterm=NONE ctermbg=255 ctermfg=36 gui=NONE guibg=#ffffff guifg=#00884c
    CSAHi Special term=bold cterm=NONE ctermbg=255 ctermfg=141 gui=NONE guibg=#ffffff guifg=#8040f0
    CSAHi Identifier term=underline cterm=NONE ctermbg=255 ctermfg=178 gui=NONE guibg=#ffffff guifg=#b07800
    CSAHi Statement term=bold cterm=NONE ctermbg=255 ctermfg=175 gui=NONE guibg=#ffffff guifg=#b64f90
    CSAHi PreProc term=underline cterm=NONE ctermbg=255 ctermfg=95 gui=NONE guibg=#ffffff guifg=#683821
    CSAHi Type term=underline cterm=bold ctermbg=255 ctermfg=126 gui=bold guibg=#ffffff guifg=#7f0055
    CSAHi FoldColumn term=NONE cterm=NONE ctermbg=254 ctermfg=242 gui=NONE guibg=#e7e7e7 guifg=#6b6b6b
    CSAHi DiffAdd term=bold cterm=NONE ctermbg=231 ctermfg=21 gui=NONE guibg=#e7e7ff guifg=#0000ff
    CSAHi DiffChange term=bold cterm=NONE ctermbg=231 ctermfg=16 gui=NONE guibg=#ffe7e7 guifg=#000000
    CSAHi DiffDelete term=bold cterm=NONE ctermbg=231 ctermfg=255 gui=NONE guibg=#e7e7ff guifg=#ffffff
    CSAHi DiffText term=reverse cterm=NONE ctermbg=231 ctermfg=196 gui=NONE guibg=#ffd0d0 guifg=#ff0000
    CSAHi SignColumn term=NONE cterm=NONE ctermbg=250 ctermfg=19 gui=NONE guibg=#bebebe guifg=#00008b
    CSAHi SpellBad term=reverse cterm=undercurl ctermbg=bg ctermfg=196 gui=undercurl guibg=bg guifg=fg guisp=Red
    CSAHi SpellCap term=reverse cterm=undercurl ctermbg=bg ctermfg=21 gui=undercurl guibg=bg guifg=fg guisp=Blue
    CSAHi SpellRare term=reverse cterm=undercurl ctermbg=bg ctermfg=201 gui=undercurl guibg=bg guifg=fg guisp=Magenta
    CSAHi SpellLocal term=underline cterm=undercurl ctermbg=bg ctermfg=37 gui=undercurl guibg=bg guifg=fg guisp=DarkCyan
elseif has("gui_running") || &t_Co == 256
    CSAHi Normal term=NONE cterm=NONE ctermbg=NONE ctermfg=16 gui=NONE guibg=#ffffff guifg=#000000
    CSAHi Underlined term=underline cterm=NONE ctermbg=NONE ctermfg=21 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi Ignore term=NONE cterm=NONE ctermbg=NONE ctermfg=231 gui=NONE guibg=#ffffff guifg=#ffffff
    CSAHi Error term=reverse cterm=NONE ctermbg=63 ctermfg=231 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi Todo term=NONE cterm=NONE ctermbg=160 ctermfg=255 gui=NONE guibg=#ffffff guifg=#ff5050
    CSAHi String term=NONE cterm=NONE ctermbg=NONE ctermfg=91 gui=NONE guibg=#ffffff guifg=#8010a0
    CSAHi Number term=NONE cterm=NONE ctermbg=NONE ctermfg=21 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi SpecialKey term=bold cterm=NONE ctermbg=NONE ctermfg=250 gui=NONE guibg=#ffffff guifg=#c0c0c0
    CSAHi NonText term=bold cterm=NONE ctermbg=254 ctermfg=242 gui=NONE guibg=#e7e7e7 guifg=#707070
    CSAHi Directory term=bold cterm=NONE ctermbg=NONE ctermfg=63 gui=NONE guibg=#ffffff guifg=#7050ff
    CSAHi ErrorMsg term=NONE cterm=NONE ctermbg=63 ctermfg=231 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi IncSearch term=reverse cterm=underline ctermbg=185 ctermfg=238 gui=underline guibg=#e0e040 guifg=#404040
    CSAHi Search term=reverse cterm=NONE ctermbg=216 ctermfg=black gui=NONE guibg=#f0c0ff guifg=#544060
    CSAHi MoreMsg term=bold cterm=NONE ctermbg=NONE ctermfg=31 gui=NONE guibg=#ffffff guifg=#0090a0
    CSAHi ModeMsg term=bold cterm=NONE ctermbg=NONE ctermfg=166 gui=NONE guibg=#ffffff guifg=#d06000
    CSAHi LineNr term=underline cterm=NONE ctermbg=230 ctermfg=242 gui=NONE guibg=#eeeeee guifg=#6b6b6b
    CSAHi Pmenu term=NONE cterm=NONE ctermbg=219 ctermfg=fg gui=NONE guibg=#ffbbff guifg=fg
    CSAHi PmenuSel term=NONE cterm=NONE ctermbg=250 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuSbar term=NONE cterm=NONE ctermbg=250 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuThumb term=NONE cterm=NONE ctermbg=16 ctermfg=231 gui=reverse guibg=bg guifg=fg
    CSAHi TabLine term=underline cterm=underline ctermbg=252 ctermfg=fg gui=underline guibg=#d3d3d3 guifg=fg
    CSAHi TabLineSel term=bold cterm=bold ctermbg=NONE ctermfg=fg gui=bold guibg=bg guifg=fg
    CSAHi TabLineFill term=reverse cterm=NONE ctermbg=16 ctermfg=231 gui=reverse guibg=bg guifg=fg
    CSAHi CursorColumn term=reverse cterm=NONE ctermbg=254 ctermfg=fg gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi CursorLine term=underline cterm=NONE ctermbg=230 ctermfg=NONE gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi Cursor term=NONE cterm=NONE ctermbg=33 ctermfg=231 gui=NONE guibg=#0080f0 guifg=#ffffff
    CSAHi CursorIM term=NONE cterm=NONE ctermbg=99 ctermfg=231 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi Question term=NONE cterm=NONE ctermbg=NONE ctermfg=93 gui=NONE guibg=#ffffff guifg=#8000ff
    CSAHi StatusLine term=reverse,bold cterm=NONE ctermbg=61 ctermfg=231 gui=NONE guibg=#4570aa guifg=#ffffff
    CSAHi StatusLineNC term=reverse cterm=NONE ctermbg=110 ctermfg=231 gui=NONE guibg=#75a0da guifg=#ffffff
    CSAHi VertSplit term=reverse cterm=NONE ctermbg=95 ctermfg=231 gui=NONE guibg=#904838 guifg=#f8f8f8
    CSAHi Title term=bold cterm=bold ctermbg=NONE ctermfg=26 gui=bold guibg=#ffffff guifg=#0033cc
    CSAHi Visual term=reverse cterm=NONE ctermbg=217 ctermfg=94 gui=NONE guibg=#ffc0a0 guifg=#804020
    CSAHi VisualNOS term=bold,underline cterm=bold,underline ctermbg=NONE ctermfg=fg gui=bold,underline guibg=bg guifg=fg
    CSAHi WarningMsg term=NONE cterm=NONE ctermbg=63 ctermfg=231 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi WildMenu term=NONE cterm=NONE ctermbg=203 ctermfg=231 gui=NONE guibg=#ff3030 guifg=#f8f8f8
    CSAHi Folded term=NONE cterm=NONE ctermbg=230 ctermfg=95 gui=NONE guibg=#fff0d0 guifg=#804030
    CSAHi lCursor term=NONE cterm=NONE ctermbg=99 ctermfg=231 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi MatchParen term=reverse cterm=NONE ctermbg=51 ctermfg=fg gui=NONE guibg=#00ffff guifg=fg
    CSAHi Comment term=bold cterm=NONE ctermbg=NONE ctermfg=34 gui=NONE guibg=#ffffff guifg=#236e25
    CSAHi Constant term=underline cterm=NONE ctermbg=NONE ctermfg=29 gui=NONE guibg=#ffffff guifg=#00884c
    CSAHi Special term=bold cterm=NONE ctermbg=NONE ctermfg=99 gui=NONE guibg=#ffffff guifg=#8040f0
    CSAHi Identifier term=underline cterm=NONE ctermbg=NONE ctermfg=136 gui=NONE guibg=#ffffff guifg=#b07800
    CSAHi Statement term=bold cterm=NONE ctermbg=NONE ctermfg=132 gui=NONE guibg=#ffffff guifg=#b64f90
    CSAHi PreProc term=underline cterm=NONE ctermbg=NONE ctermfg=58 gui=NONE guibg=#ffffff guifg=#683821
    CSAHi Type term=underline cterm=bold ctermbg=NONE ctermfg=89 gui=bold guibg=#ffffff guifg=#7f0055
    CSAHi FoldColumn term=NONE cterm=NONE ctermbg=254 ctermfg=242 gui=NONE guibg=#e7e7e7 guifg=#6b6b6b
    CSAHi DiffAdd term=bold cterm=NONE ctermbg=189 ctermfg=21 gui=NONE guibg=#e7e7ff guifg=#0000ff
    CSAHi DiffChange term=bold cterm=NONE ctermbg=224 ctermfg=16 gui=NONE guibg=#ffe7e7 guifg=#000000
    CSAHi DiffDelete term=bold cterm=NONE ctermbg=189 ctermfg=231 gui=NONE guibg=#e7e7ff guifg=#ffffff
    CSAHi DiffText term=reverse cterm=NONE ctermbg=224 ctermfg=196 gui=NONE guibg=#ffd0d0 guifg=#ff0000
    CSAHi SignColumn term=NONE cterm=NONE ctermbg=250 ctermfg=18 gui=NONE guibg=#bebebe guifg=#00008b
    CSAHi SpellBad term=reverse cterm=undercurl ctermbg=NONE ctermfg=196 gui=undercurl guibg=bg guifg=fg guisp=Red
    CSAHi SpellCap term=reverse cterm=undercurl ctermbg=NONE ctermfg=21 gui=undercurl guibg=bg guifg=fg guisp=Blue
    CSAHi SpellRare term=reverse cterm=undercurl ctermbg=NONE ctermfg=201 gui=undercurl guibg=bg guifg=fg guisp=Magenta
    CSAHi SpellLocal term=underline cterm=undercurl ctermbg=NONE ctermfg=30 gui=undercurl guibg=bg guifg=fg guisp=DarkCyan
elseif has("gui_running") || &t_Co == 88
    CSAHi Normal term=NONE cterm=NONE ctermbg=79 ctermfg=16 gui=NONE guibg=#ffffff guifg=#000000
    CSAHi Underlined term=underline cterm=NONE ctermbg=79 ctermfg=19 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi Ignore term=NONE cterm=NONE ctermbg=79 ctermfg=79 gui=NONE guibg=#ffffff guifg=#ffffff
    CSAHi Error term=reverse cterm=NONE ctermbg=19 ctermfg=79 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi Todo term=NONE cterm=NONE ctermbg=79 ctermfg=69 gui=NONE guibg=#ffffff guifg=#ff5050
    CSAHi String term=NONE cterm=NONE ctermbg=79 ctermfg=33 gui=NONE guibg=#ffffff guifg=#8010a0
    CSAHi Number term=NONE cterm=NONE ctermbg=79 ctermfg=19 gui=NONE guibg=#ffffff guifg=#0000ff
    CSAHi SpecialKey term=bold cterm=NONE ctermbg=79 ctermfg=85 gui=NONE guibg=#ffffff guifg=#c0c0c0
    CSAHi NonText term=bold cterm=NONE ctermbg=87 ctermfg=82 gui=NONE guibg=#e7e7e7 guifg=#707070
    CSAHi Directory term=bold cterm=NONE ctermbg=79 ctermfg=39 gui=NONE guibg=#ffffff guifg=#7050ff
    CSAHi ErrorMsg term=NONE cterm=NONE ctermbg=19 ctermfg=79 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi IncSearch term=reverse cterm=underline ctermbg=56 ctermfg=80 gui=underline guibg=#e0e040 guifg=#404040
    CSAHi Search term=reverse cterm=NONE ctermbg=75 ctermfg=33 gui=NONE guibg=#f0c0ff guifg=#544060
    CSAHi MoreMsg term=bold cterm=NONE ctermbg=79 ctermfg=21 gui=NONE guibg=#ffffff guifg=#0090a0
    CSAHi ModeMsg term=bold cterm=NONE ctermbg=79 ctermfg=52 gui=NONE guibg=#ffffff guifg=#d06000
    CSAHi LineNr term=underline cterm=NONE ctermbg=87 ctermfg=82 gui=NONE guibg=#eeeeee guifg=#6b6b6b
    CSAHi Pmenu term=NONE cterm=NONE ctermbg=75 ctermfg=fg gui=NONE guibg=#ffbbff guifg=fg
    CSAHi PmenuSel term=NONE cterm=NONE ctermbg=85 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuSbar term=NONE cterm=NONE ctermbg=85 ctermfg=fg gui=NONE guibg=#bebebe guifg=fg
    CSAHi PmenuThumb term=NONE cterm=NONE ctermbg=16 ctermfg=79 gui=reverse guibg=bg guifg=fg
    CSAHi TabLine term=underline cterm=underline ctermbg=86 ctermfg=fg gui=underline guibg=#d3d3d3 guifg=fg
    CSAHi TabLineSel term=bold cterm=bold ctermbg=bg ctermfg=fg gui=bold guibg=bg guifg=fg
    CSAHi TabLineFill term=reverse cterm=NONE ctermbg=16 ctermfg=79 gui=reverse guibg=bg guifg=fg
    CSAHi CursorColumn term=reverse cterm=NONE ctermbg=87 ctermfg=fg gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi CursorLine term=underline cterm=NONE ctermbg=230 ctermfg=NONE gui=NONE guibg=#e5e5e5 guifg=fg
    CSAHi Cursor term=NONE cterm=NONE ctermbg=23 ctermfg=79 gui=NONE guibg=#0080f0 guifg=#ffffff
    CSAHi CursorIM term=NONE cterm=NONE ctermbg=35 ctermfg=79 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi Question term=NONE cterm=NONE ctermbg=79 ctermfg=35 gui=NONE guibg=#ffffff guifg=#8000ff
    CSAHi StatusLine term=reverse,bold cterm=NONE ctermbg=21 ctermfg=79 gui=NONE guibg=#4570aa guifg=#ffffff
    CSAHi StatusLineNC term=reverse cterm=NONE ctermbg=38 ctermfg=79 gui=NONE guibg=#75a0da guifg=#ffffff
    CSAHi VertSplit term=reverse cterm=NONE ctermbg=36 ctermfg=79 gui=NONE guibg=#904838 guifg=#f8f8f8
    CSAHi Title term=bold cterm=bold ctermbg=79 ctermfg=18 gui=bold guibg=#ffffff guifg=#0033cc
    CSAHi Visual term=reverse cterm=NONE ctermbg=73 ctermfg=32 gui=NONE guibg=#ffc0a0 guifg=#804020
    CSAHi VisualNOS term=bold,underline cterm=bold,underline ctermbg=bg ctermfg=fg gui=bold,underline guibg=bg guifg=fg
    CSAHi WarningMsg term=NONE cterm=NONE ctermbg=19 ctermfg=79 gui=NONE guibg=#4040ff guifg=#f8f8f8
    CSAHi WildMenu term=NONE cterm=NONE ctermbg=64 ctermfg=79 gui=NONE guibg=#ff3030 guifg=#f8f8f8
    CSAHi Folded term=NONE cterm=NONE ctermbg=78 ctermfg=32 gui=NONE guibg=#fff0d0 guifg=#804030
    CSAHi lCursor term=NONE cterm=NONE ctermbg=35 ctermfg=79 gui=NONE guibg=#8040ff guifg=#ffffff
    CSAHi MatchParen term=reverse cterm=NONE ctermbg=31 ctermfg=fg gui=NONE guibg=#00ffff guifg=fg
    CSAHi Comment term=bold cterm=NONE ctermbg=79 ctermfg=20 gui=NONE guibg=#ffffff guifg=#236e25
    CSAHi Constant term=underline cterm=NONE ctermbg=79 ctermfg=21 gui=NONE guibg=#ffffff guifg=#00884c
    CSAHi Special term=bold cterm=NONE ctermbg=79 ctermfg=35 gui=NONE guibg=#ffffff guifg=#8040f0
    CSAHi Identifier term=underline cterm=NONE ctermbg=79 ctermfg=52 gui=NONE guibg=#ffffff guifg=#b07800
    CSAHi Statement term=bold cterm=NONE ctermbg=79 ctermfg=53 gui=NONE guibg=#ffffff guifg=#b64f90
    CSAHi PreProc term=underline cterm=NONE ctermbg=79 ctermfg=32 gui=NONE guibg=#ffffff guifg=#683821
    CSAHi Type term=underline cterm=bold ctermbg=79 ctermfg=33 gui=bold guibg=#ffffff guifg=#7f0055
    CSAHi FoldColumn term=NONE cterm=NONE ctermbg=87 ctermfg=82 gui=NONE guibg=#e7e7e7 guifg=#6b6b6b
    CSAHi DiffAdd term=bold cterm=NONE ctermbg=79 ctermfg=19 gui=NONE guibg=#e7e7ff guifg=#0000ff
    CSAHi DiffChange term=bold cterm=NONE ctermbg=79 ctermfg=16 gui=NONE guibg=#ffe7e7 guifg=#000000
    CSAHi DiffDelete term=bold cterm=NONE ctermbg=79 ctermfg=79 gui=NONE guibg=#e7e7ff guifg=#ffffff
    CSAHi DiffText term=reverse cterm=NONE ctermbg=74 ctermfg=64 gui=NONE guibg=#ffd0d0 guifg=#ff0000
    CSAHi SignColumn term=NONE cterm=NONE ctermbg=85 ctermfg=17 gui=NONE guibg=#bebebe guifg=#00008b
    CSAHi SpellBad term=reverse cterm=undercurl ctermbg=bg ctermfg=64 gui=undercurl guibg=bg guifg=fg guisp=Red
    CSAHi SpellCap term=reverse cterm=undercurl ctermbg=bg ctermfg=19 gui=undercurl guibg=bg guifg=fg guisp=Blue
    CSAHi SpellRare term=reverse cterm=undercurl ctermbg=bg ctermfg=67 gui=undercurl guibg=bg guifg=fg guisp=Magenta
    CSAHi SpellLocal term=underline cterm=undercurl ctermbg=bg ctermfg=21 gui=undercurl guibg=bg guifg=fg guisp=DarkCyan
endif

if 1
    delcommand CSAHi
endif
