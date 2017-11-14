
CREATE DATABASE DAW202_DBdepartamentos;
USE DAW202DB_departamentos;
CREATE TABLE Departamento(
	CodDepartamento VARCHAR(3) PRIMARY KEY,
	DescDepartamento VARCHAR(255),
	FechaBaja DATE
)ENGINE=InnoDB;


