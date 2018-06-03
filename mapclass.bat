@echo off
setlocal
:PROMPT
SET /P AREYOUSURE=Are you sure run autoload? (Y/[N])?
IF /I "%AREYOUSURE%" NEQ "Y" GOTO END
composer dump-autoload -o
:END
endlocal