@echo off
Chcp 65001

set fromAssets=c:\OpenServer\domains\QTT\assets\*
set toAssets=c:\OpenServer\domains\dualView\QTT\assets\*

set fromconnector=c:\OpenServer\domains\QTT\connector\*
set toconnector=c:\OpenServer\domains\dualView\QTT\connector\*

set fromcore=c:\OpenServer\domains\QTT\core\*
set tocore=c:\OpenServer\domains\dualView\QTT\core\*

set fromweb=c:\OpenServer\domains\QTT\web\*
set toweb=c:\OpenServer\domains\dualView\QTT\web\*

set fromroot=c:\OpenServer\domains\QTT\*
set toroot=c:\OpenServer\domains\dualView\QTT\*

xcopy  %fromAssets% %toAssets% /V /Y /S /Q
xcopy %fromconnector% %toconnector% /V /Y /S /Q
xcopy %fromcore% %tocore% /V /Y /S /Q
xcopy %fromweb% %toweb%  /V /Y /S /Q
xcopy %fromroot% %toroot%  /V /Y /Q /exclude:excludedfileslist.txt
color 79
cls
echo.
echo ╔══╗╔══╗╔═══╗╔╗╔╗──╔══╗╔══╗──╔══╗╔══╗╔╗──╔╗╔═══╗╔╗──╔═══╗╔════╗╔═══╗╔══╗
echo ║╔═╝║╔╗║║╔═╗║║║║║──╚╗╔╝║╔═╝──║╔═╝║╔╗║║║──║║║╔═╗║║║──║╔══╝╚═╗╔═╝║╔══╝║╔╗╚╗
echo ║║──║║║║║╚═╝║║╚╝║───║║─║╚═╗──║║──║║║║║╚╗╔╝║║╚═╝║║║──║╚══╗──║║──║╚══╗║║╚╗║
echo ║║──║║║║║╔══╝╚═╗║───║║─╚═╗║──║║──║║║║║╔╗╔╗║║╔══╝║║──║╔══╝──║║──║╔══╝║║─║║
echo ║╚═╗║╚╝║║║────╔╝║──╔╝╚╗╔═╝║──║╚═╗║╚╝║║║╚╝║║║║───║╚═╗║╚══╗──║║──║╚══╗║╚═╝║
echo ╚══╝╚══╝╚╝────╚═╝──╚══╝╚══╝──╚══╝╚══╝╚╝──╚╝╚╝───╚══╝╚═══╝──╚╝──╚═══╝╚═══╝
echo ╔══════════════════════════════════════
echo ║FROM
echo ║- %fromAssets%
echo ║- %fromconnector%
echo ║- %fromcore%
echo ║- %fromweb%
echo ║- %fromroot%
echo ╚═══════════════════════════════════════════
echo.
echo ╔══════════════════════════════════════
echo ║TO
echo ║- %toAssets%
echo ║- %toconnector%
echo ║- %tocore%
echo ║- %toweb%
echo ║- %toroot%
echo ╚═══════════════════════════════════════════
echo.
echo.
echo.
PAUSE
