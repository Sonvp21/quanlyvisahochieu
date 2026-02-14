Set shell = CreateObject("Wscript.Shell")
shell.CurrentDirectory = "F:\project\quanlyvisahochieu"
shell.Run "cmd /c C:\Users\PV\.config\herd\bin\php84\php.exe artisan schedule:run >> storage\logs\scheduler.log 2>&1", 0, True
