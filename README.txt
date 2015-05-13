===== OPIS INSTALACJI APLIKACJI =====

1. Wypakować katalogi /src i /web oraz pliki composer.json i composer.phar do folderu /sciezka/do/projektu.
2. W terminalu wpisać komendy
 $cd /sciezka/do/projektu
 $php composer.phar install
 by zainstalować potrzebne komponenty.
3. Zaimportować bazę danych na serwer z pliku data.sql. 
4. W pliku /sciezka/do/projektu/web/index.php należy w linijkach od 28 do 33 zastąpić dane defaultowe własnymi danymi dostępowymi do bazy danych.
5. W pliku /sciezka/do/projektu/web/.htaccess należy wyedytować linijkę 4, która wskazuje na ścieżkę do katalogu web, podając ścieżkę w której umieściło się projekt.
6. W pliku /sciezka/do/projektu/web/js/character.js w linijce 180 i 300 podać odpowiednią ścieżkę url zamiast defaultowego localhosta.
6. Grać, grać i jeszcze raz grać! Za pomocą strzałek steruje się bohaterem, klawiszem spacji stawia się bombę. Na planszy mogą być maksymalnie dwie bomby. Uwaga na żarłoczne misie!

===== DODATKOWE INFORMACJE =====

1. Dane administratora: login - john.doe
   hasło - John0Doe1
2. Projekt znajduje się też na wierzbie, pod adresem http://wierzba.wzks.uj.edu.pl/~12_wierzbiak/bomberwoman/web/
3. Dokumentacja projektu jest dostępna w archiwum dokumentacja.zip, należy go wypakować, a plik index.html otworzyć w przeglądarce.