-- This file executes SQL DDL. 
-- This file is automatically run when you run docker-compose up -d on your terminal. 


-- Following is the DDLe statements only for testing purposes. 
-- Actual statements will be very defferent. 

CREATE TABLE test (
    eno CHAR(5), 
    ename VARCHAR(30) NOT NULL, 
    bdate DATE, 
    title CHAR(2), 
    salary DECIMAL(9,2), 
    supereno CHAR(5), 
    dno CHAR(5), 
    PRIMARY KEY (eno)
);

INSERT INTO test (eno, ename) VALUES ("E0001", "Yuki Isomura"); 
INSERT INTO test (eno, ename) VALUES ("E0002", "Taii Hirano"); 
INSERT INTO test (eno, ename) VALUES ("E0003", "Adams Chen"); 

