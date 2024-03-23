import mysql.connector

try:
    cnx = mysql.connector.connect(user='testuser', 
                                  password='atytestpw',
                                  host='localhost', 
                                  database='testdb')
    cursor = cnx.cursor()
    print("db connected")

    # query = ("CREATE TABLE test (" +
    #          "eno CHAR(5), ename VARCHAR(30) NOT NULL, PRIMARY KEY (eno))")
    # cursor.execute(query)
    # print('Table test created')

    query = ("INSERT INTO test(eno, ename) VALUES (\"abcde\", \"Mr.Random\")")
    cursor.execute(query)
    cnx.commit()    # Store the insert to database permanently 
    print('Inserted')

    query = ("SELECT eno, ename FROM test")
    cursor.execute(query)
    for (eno, ename) in cursor:
        print(eno)
        print(ename)
    print("Select executed")

    # query = ("DROP TABLE test")
    # cursor.execute(query)
    # print("Table test dropped")
    
    cursor.close()
except mysql.connector.Error as err:
    print(err)
finally:
    cnx.close()
