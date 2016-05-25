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
`$projects = $sql->table('projects')->select('*')->where('name', 'like', '%Test%')->orWhere('title', '=', 'Testingproject')->get();`<br>
Generally it works as the Laravel Eloquent Builder, which it is based on.<br>
###Available Statements
`public function insert(array $columns, array $values);`<br>
`public function create($table, $closure)`<br>
`public function update($id, array $columns, array $values);`<br>
`public function drop($table);`<br>
`public function table($table);`<br>
`public function select($select = '*');`<br>
`public function where($column, $operator, $value = NULL);`<br>
`public function orWhere($column, $operator, $value = NULL);`<br>
`public function whereIn($column, array $values);`<br>
`public function join($table, $primary, $operator, $other = NULL);`<br>
`public function leftJoin($table, $primary, $operator, $other = NULL);`<br>
`public function rightJoin($table, $primary, $operator, $other = NULL);`<br>
`public function outerJoin($table, $primary, $operator, $other = NULL);`<br>
`public function fullOuterJoin($table, $primary, $operator, $other = NULL);`<br>
`public function orderBy($column, $order = 'desc');`<br>
`public function all()`<br>
`public function raw($sql);`<br>
In some cases the operator may also be the value, in that case the operator will be set to '=' by default.<br>
###Create function closure
When you want to use the "create" function, you need to pass in a closure or anonymous function as second argument.<br>
This function takes one parameter:<br>
`function($table){}`<br>
The table parameter is a "Blueprint" instance, which is used to define certain fields in you table.<br>
A basic example of a create query could look like this:<br>
`$sql->create('projects', function(Blueprint $table){
	$table->increments();
	$table->string('name');
	$table->timestamps();
});`<br>
####Blueprint functions
`public function increments($name = 'id');`<br>
`public function string($name, $null = false, $length = 255);`<br>
`public function integer($name, $null = false, $unsigned = false);`<br>
`public function text($name, $null = false);`<br>
`public function custom($customquery);`<br>
`public function timestamps();`<br>
A custom query must be implemented as one column definition:<br>
`tinyint(1) DEFAULT 0 NOT NULL`<br>