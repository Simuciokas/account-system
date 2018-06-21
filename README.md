# account-system

Initial information (en):

Create a simple client registration sistem. Registration should be done over the console (CLI). More on how to use PHP and CLI -http://php.net/manual/en/features.commandline.php.
Inputs that need to be filled:
firstname;lastname;email;phonenumber1;phonenumber2;comment;
Inputs have to be written to a MySQL database or a file

Console has to have these functions:
- add account
- edit account
- delete account

Bonus points for:
- No framework (Laravel, Symfony , Zend and etc.)
- Validation (valid and unique Email, valid phone number)
- A few unit tests (PHPUnit framework can be used for this)
- A function to import .csv file to create an account and create an example .csv file

Pradinė informacija (lt):

Sukurkite paprastą klientų registravimo sistemą. Registracija turėtų būti vykdoma konsolėje (CLI).
Plačiau apie tai, kaip naudoti PHP ir CLI - http://php.net/manual/en/features.commandline.php
Laukai, kuriuos reikia užpildyti:
firstname;lastname;email;phonenumber1;phonenumber2;comment;
Duomenys turėtų būti įrašyti į MySQL duomenų bazę arba failą.

Konsolėje turi būti galimybė įvykdyti šiuos veiksmus:
* Pridėti klientą
* Redaguoti klientą
* Ištrinti klientą

Bonus taškai už:
* Jokio framework'o nenaudojimą (Laravel, Symfony, Zend ir panašūs)
* Validacijas (validus ir unikalus email'as, validus telefono numeris)
* Bent kelis Unit testus (tam gali būti naudojamas PHPUnit framework'as)
* Pridėtą galimybę importuoti .csv formatu. Pavyzdinis .csv failas turėtų būti pridėtas prie kodo