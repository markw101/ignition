<html>
<head>
<script src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("jquery", "1");
</script>
<script type="text/javascript">
var db;

$(function(){
    db = openDatabase('HelloWorld');

    db.transaction(
        function(transaction) {
            transaction.executeSql(
                'CREATE TABLE IF NOT EXISTS Table1 ' +
                '  (TableID INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, ' +
                '   Field1 TEXT NOT NULL );'
            );
        }
    );

    db.transaction(
        function(transaction) {
            transaction.executeSql(
                'SELECT * FROM Table1;',function (transaction, result) {
                    for (var i=0; i < result.rows.length; i++) {
            alert('1');
                        $('body').append(result.rows.item(i));
                    }
                }, 
                errorHandler
            );
        }
    );

    $('form').submit(function() {
        var xxx = $('#xxx').val();
        db.transaction(
            function(transaction) {
                transaction.executeSql(
                'INSERT INTO Table1 (Field1) VALUES (?);', [xxx], function(){
                    alert('Saved!');
                }, 
                errorHandler
                );
            }
        );
        return false;
    });
});

function errorHandler(transaction, error) {
    alert('Oops. Error was '+error.message+' (Code '+error.code+')');
    transaction.executeSql('INSERT INTO errors (code, message) VALUES (?, ?);', 
    [error.code, error.message]);
    return false;
}
</script>
</head>
<body>
<form method="post">
    <input name="xxx" id="xxx" />
    <p>
    <input type="submit" name="OK" />
    </p>
    <a href="http://www.google.com">Cancel</a>
</form>
</body>
</html>