" indenthl.vim: hilights each indent level in different colors.
" Author: Dane Summers
" Date: Feb 15, 2007
" Version: 2
" 
" See :help mysyntaxfile-add for how to install this file.

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
syn match myPhpFunction /\(^\|\s\)\@<=function\(\s\|$\)\@=/ containedin=ALL
syn match myPhpFunctionName /\(function\s\+\)\@<=[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/ containedin=ALL
"syn match myPhpVariable /\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/ containedin=ALL
syn match spaceStart /^\t* \+/ containedin=ALL
syn match spaceEnd /\s\+$/ containedin=ALL
"syn match spaceStart /^\t* \+/ containedin=ALL


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
    hi clear myPhpFunction
    hi clear myPhpFunctionName
    hi clear myPhpVariable
    hi clear spaceStart
    hi clear spaceEnd
  else
    let g:TabsHl = 1
    command! -nargs=+ HiLink hi def <args>
    "command -nargs=+ HiLink hi link <args>
    " to make colors slightly darker at each level (in gui)
"    HiLink cTab1 term=NONE cterm=NONE ctermbg=255 gui=NONE guibg=gray90
"    HiLink cTab2 term=NONE cterm=NONE ctermbg=254 gui=NONE guibg=gray85
"    HiLink cTab3 term=NONE cterm=NONE ctermbg=253 gui=NONE guibg=gray80
"    HiLink cTab4 term=NONE cterm=NONE ctermbg=252 gui=NONE guibg=gray75
"    HiLink cTab5 term=NONE cterm=NONE ctermbg=251 gui=NONE guibg=gray70
"    HiLink cTab6 term=NONE cterm=NONE ctermbg=250 gui=NONE guibg=gray65
"    HiLink cTab7 term=NONE cterm=NONE ctermbg=249 gui=NONE guibg=gray60
"    HiLink cTab8 term=NONE cterm=NONE ctermbg=248 gui=NONE guibg=gray55
"    HiLink cTab9 term=NONE cterm=NONE ctermbg=247 gui=NONE guibg=gray50
"    HiLink cTab10 term=NONE cterm=NONE ctermbg=246 gui=NONE guibg=gray45
"    HiLink cTab10 term=NONE cterm=NONE ctermbg=245 gui=NONE guibg=gray45

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

    HiLink myPhpFunction term=NONE cterm=NONE ctermfg=81 ctermbg=NONE gui=NONE guibg=gray60
    HiLink myPhpFunctionName term=NONE cterm=NONE ctermfg=147 ctermbg=NONE gui=NONE guibg=gray60
"    HiLink myPhpVariable term=NONE cterm=NONE ctermfg=120 ctermbg=NONE gui=NONE guibg=gray60

    HiLink spaceStart term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
    HiLink spaceEnd term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
	delcommand HiLink
  endif
endfunction
nmap <F9> :call <SID>MyHl()<CR>
if !exists("g:TabsHl")
  let g:TabsHl = 0
  call s:MyHl()
endif


"hi phpIdentifier term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierComplex term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMethodsVar term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpRelation term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpList term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpParent term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpParentError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpObjectOperator term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpLabel term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFoldTry term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFoldCatch term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStorageClass term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpAssign term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSemicolon term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStructureHere term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMemberHere term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMethodHere term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpPropertyHere term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpComment term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpOperator term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpTernaryRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpNull term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBoolean term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpNumber term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpOctalError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFloat term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStringSingle term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpQuoteSingle term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStringDoubleConstant term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSpecialChar term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStringDouble term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpQuoteDouble term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBacktick term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpQuoteBacktick term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpHereDoc term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSpecialCharfold term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpHereDocDelimiter term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStringLiteral term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStringRegular term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpPropertyInString term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpPropertyInStringError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierInString term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBracketInString term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBracketInStringFalseStart term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierIndexInString term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierIndexInStringError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpVarSelector term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpPropertySelector term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierInStringComplex term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierErratic term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierInStringErratic term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpErraticBracketRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpIdentifierInStringErraticError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpVarSelectorDeref term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpVarSelectorError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDerefInvalid term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSuperglobal term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSuperglobal term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBuiltinVar term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpLongVar term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpEnvVar term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpType term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStaticUsage term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStaticAccess term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStaticVariable term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStaticCall term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDoubleColon term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefine term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMagicClass term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpConditional term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpRepeat term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpForeachRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpAs term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpForRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpConstructRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSwitchConstructRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSemicolonNotAllowedHere term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDoBlock term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDoWhile term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDoWhileConstructRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStatement term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpCase term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStatementRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpCaseRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSemicolonError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpColonError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMagicConstant term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpCoreConstant term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFunctions term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpClasses term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMethods term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpClassDefine term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpArrayPair term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpArrayComma term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpListComma term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpArray term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpInstanceof term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMemberSelector term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpTodo term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpForSemicolon term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSwitchBlock term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFoldFunction term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFoldClass term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFoldInterface term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpRegionDelimiter term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStructureType term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStructure term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefineClassName term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefineInterfaceName term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefineFuncByRef term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefineFuncProto term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpStorageClass term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefineMethod term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpClassBlock term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDefineMethodByRef term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSpecialFunction term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpException term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpCatchRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSCKeyword term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFCKeyword term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpFoldHtmlInside term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpEchoRegion term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpEchoComma term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpEcho term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpPrint term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpInclude term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSpecialMethods term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSPLMethods term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpInterfaces term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpAssignByRef term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSupressErrors term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSyncStartOfFile term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSyncComment term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpSyncString term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpRegionSync term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpArrayParens term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpArrayPairError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpControlParent term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBrace term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBraceError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBraceFunc term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBraceClass term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpBraceException term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpDynamicSelector term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpMemberError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
"hi phpHTMLError term=NONE cterm=NONE ctermbg=160 gui=NONE guibg=gray60
