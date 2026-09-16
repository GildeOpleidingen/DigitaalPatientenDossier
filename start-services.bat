@echo off
title Start Apache2 and MySQL in WSL

echo ===================================================
echo   Starting Apache2 and MySQL in WSL (Ubuntu)...
echo ===================================================
echo.

wsl -u root service apache2 start
wsl -u root service mysql start

echo.
echo ===================================================
echo   Current Service Status:
echo ===================================================
wsl -u root service apache2 status
wsl -u root service mysql status

echo.
echo ===================================================
echo   Services are running!
echo ===================================================
echo.
pause
