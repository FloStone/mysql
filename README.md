#MySQL helper for any PHP Project
##Installation
`require flo5581/mysql-plainphp`<br>
##Usage
Include the class using<br>
`use Flo\MySQL\MySQL;`<br>
Connect to the database using<br>
`$sql = MySQL::connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);`<br>
Now execute queries with the query function<br>
`$results = $sql->query("SELECT * FROM projects");`<br>
The results will always be returned immediately however you can also get the results using<br>
`$results = $sql->results();`