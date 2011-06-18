:let php_folding = 0
:let php_strict_blocks = 0
set colorcolumn=80
set showbreak=
set incsearch
let &titleold="bash"
let $VIMHOME="/home/amaryanov/.vim"
set ts=4
set sw=4
set number
set ai
syn on
set ignorecase
" set mouse=a
set backspace=2
set statusline=%<%F\ %r%h%y[%{&ff}][%{&encoding}]%m%=%-13.(%5l_%3c%V%)%5LL%8.P
"set statusline=%<%F%h%m%r%h%w%y\ %{&ff}\ %{strftime(\"%c\",getftime(expand(\"%:p\")))}%=\ lin:%l\,%L\ col:%c%V\ pos:%o\ ascii:%b\ %P
set laststatus=2
set showcmd " show unfinished command.
set wildmenu
set number
set nohidden " Safe! :)
set wrap
set sidescroll=5        " smooth scrolling (if text is not wrapped).
set sidescrolloff=5
set listchars+=precedes:<,extends:>     " chows arrows <,> if part of line is shown.
set ai "autoindent
set cindent "autoindent
"set cursorline
set t_Co=256
"colorscheme elfmode
if &t_Co > 2 || has("gui_running")
  syntax on
endif
if &t_Co == 256
  colorscheme maryanov
endif

let Tlist_Ctags_Cmd = '/home/amaryanov/build/ctags/bin/ctags'
nmap <F2> :TlistToggle<CR>
inoremap <F2> <ESC>:TlistToggle<CR>
nmap <F3> o@file_put_contents('/home/amaryanov/test.out', __FILE__.':'.__LINE__ . ' ' . date('c') . ' ' . print_r(, 1)."\n", FILE_APPEND \| FILE_TEXT);<esc>9bli
inoremap <F3> <esc>o@file_put_contents('/home/amaryanov/test.out', __FILE__.':'.__LINE__ . ' ' . date('c') . ' ' . print_r(, 1)."\n", FILE_APPEND \| FILE_TEXT);<esc>9bli
nmap <F8> :set hlsearch<CR>
nmap <C-F8> :set nohlsearch<CR>
nmap <F5> :ls<CR>
inoremap <F5> <ESC>:ls<CR>
nmap <F6> :bn!<CR>
inoremap <F6> <ESC>:bn!<CR>
nmap <F7> :bp!<CR>
inoremap <F7> <ESC>:bp!<CR>
vmap <F11> :s/^/\/\//<CR>
vmap <F12> :s/^\/\///<CR>
let Tlist_Sort_Type="name"
let Tlist_GainFocus_On_ToggleOpen = 1
let Tlist_Compact_Format = 1
let Tlist_Auto_Update = 1

let g:netrw_sort_sequence='[\/]$,\.bak$,\.php$,\.o$,\.h$,\.info$,\.obj$,\.diff$,\.sw.$,*'
let g:netrw_liststyle=1

set hlsearch
"exe "set title titlestring=%F%m"
"exe "set title t_ts=\<ESC>k t_fs=\<ESC>\\"
ab wq w

source ~/.vim/php-doc.vim
inoremap <C-P> <ESC>:call PhpDocSingle()<CR>i 
nnoremap <C-P> :call PhpDocSingle()<CR> 
vnoremap <C-P> :call PhpDocRange()<CR> 

inoremap <C-J> <ESC>:call RunPhpcs()<CR>i 
nnoremap <C-J> :call RunPhpcs()<CR> 
vnoremap <C-J> :call RunPhpcs()<CR> 

if has("multi_byte")
	if &termencoding == ""
		let &termencoding = &encoding
	endif
	set encoding=utf-8
	setglobal fileencoding=utf-8 bomb
	set fileencodings=ucs-bom,utf-8,latin1
endif
