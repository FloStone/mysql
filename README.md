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
##Using Statements
If you want to make it a little easier you can also use several statements as functions.<br>
E.g. if you wanted to make a where clause you could also write<br>
`$projects = $sql->select('*')->where('id', '=', 1)->get();`<br>
In order to use these statements you first have to specify a table:<br>
`$sql->table('projects');`<br>
The table name will stay the same for every query until you eventually change it.<br>
You can also chain the table with several statements:<br>
`$projects = $sql->table('projects')->select('*')->where('name', 'like', '%Test%')->where('title', '=', 'Testingproject')->get();`<br>
Generally it works as the Laravel Eloquent Builder, which it is based on.<br>
###Available Statements
`public function table($table);`<br>
`public function select($select = '*');`<br>
`public function where($column, $operator, $value = NULL);`<br>
`public function orWhere($column, $operator, $value = NULL);`<br>
`public function whereIn($column, array $values);`<br>
`public function insert(array $columns, array $values);`<br>
In some cases the operator may also be the value, in that case the operator will be set to '=' by default.<br>