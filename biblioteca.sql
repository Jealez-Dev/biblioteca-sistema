-- 1. TABLAS INDEPENDIENTES (Catálogos y Entidades Fuertes)

CREATE TABLE Usuario (
    DNI INT PRIMARY KEY, -- [cite: 102, 132]
    Nombre VARCHAR(20), -- [cite: 132]
    Apellido VARCHAR(20), -- [cite: 132]
    Edad INT, -- [cite: 132]
    Correo VARCHAR(20), -- [cite: 132]
    Num_Telefono INT -- [cite: 132]
);

CREATE TABLE Catg_de_User_SA (
    ID INT PRIMARY KEY, -- [cite: 105, 133]
    Tipo VARCHAR(20), -- [cite: 133]
    N_de_dias INT, -- [cite: 105, 133]
    N_de_ejemplares INT -- [cite: 105, 133]
);

CREATE TABLE Autores (
    Cutter VARCHAR(20) PRIMARY KEY, -- [cite: 111, 133]
    Nombre VARCHAR(20), -- [cite: 133]
    Nacionalidad VARCHAR(20) -- [cite: 133, 134]
);

CREATE TABLE Editorial (
    ID INT PRIMARY KEY, -- [cite: 115, 133]
    Nombre VARCHAR(20), -- [cite: 133]
    SedeMatriz VARCHAR(20), -- [cite: 115, 133]
    Email VARCHAR(20), -- [cite: 115, 133]
    Telefono INT -- [cite: 115, 133]
);

CREATE TABLE T_Obras (
    ID INT PRIMARY KEY, -- [cite: 116, 134]
    Descripcion VARCHAR(20), -- [cite: 134]
    Prestar BOOLEAN -- [cite: 134]
);

CREATE TABLE CDD (
    Codigo INT PRIMARY KEY, -- [cite: 117, 134]
    Descripcion VARCHAR(20) -- [cite: 134]
);

CREATE TABLE Estado (
    ID INT PRIMARY KEY, -- [cite: 108, 133]
    Descripcion VARCHAR(20) -- [cite: 108, 133]
);

CREATE TABLE T_soporte (
    ID INT PRIMARY KEY, -- [cite: 127, 133]
    Descripcion VARCHAR(20), -- [cite: 127, 133]
    Prestable BOOLEAN -- [cite: 127, 133]
);


-- 2. TABLAS DEPENDIENTES (Con Llaves Foráneas)

CREATE TABLE Admin (
    DNI_Usuario INT PRIMARY KEY, -- [cite: 107]
    Username VARCHAR(20) UNIQUE, -- [cite: 107, 132]
    Password VARCHAR(20), -- [cite: 107, 132]
    FOREIGN KEY (DNI_Usuario) REFERENCES Usuario(DNI)
);

CREATE TABLE Usuario_sin_acceso (
    DNI_Usuario INT PRIMARY KEY, -- [cite: 104]
    Carrera_Departamento VARCHAR(20), -- [cite: 104, 132]
    ID_Catg_de_User_SA INT, -- [cite: 104]
    FOREIGN KEY (DNI_Usuario) REFERENCES Usuario(DNI),
    FOREIGN KEY (ID_Catg_de_User_SA) REFERENCES Catg_de_User_SA(ID)
);

-- Tabla para la relación recursiva "Tiene" del CDD [cite: 119, 120]
CREATE TABLE CDD_Tiene (
    Codigo INT, -- [cite: 121, 134]
    Codigo_Padre VARCHAR(20), -- [cite: 122, 134]
    PRIMARY KEY (Codigo, Codigo_Padre),
    FOREIGN KEY (Codigo) REFERENCES CDD(Codigo)
);

CREATE TABLE Obras (
    Año INT, -- [cite: 110, 133]
    Cutter_Autor VARCHAR(20), -- [cite: 110]
    Codigo_CDD INT, -- [cite: 110]
    Titulo VARCHAR(20), -- [cite: 110, 133]
    Edicion VARCHAR(20), -- [cite: 110, 133]
    Otros_Autores VARCHAR(20), -- [cite: 110, 133]
    ISBN INT, -- [cite: 110, 133]
    ISNN INT, -- [cite: 110, 133]
    Descripcion VARCHAR(20), -- [cite: 110, 133]
    L_Publicacion VARCHAR(20), -- [cite: 110, 133]
    N_Dep_Legal VARCHAR(20), -- [cite: 110, 133]
    ID_Editorial INT, -- [cite: 110]
    ID_T_Obras INT, -- [cite: 110]
    PRIMARY KEY (Año, Cutter_Autor, Codigo_CDD), 
    FOREIGN KEY (Cutter_Autor) REFERENCES Autores(Cutter),
    FOREIGN KEY (Codigo_CDD) REFERENCES CDD(Codigo),
    FOREIGN KEY (ID_Editorial) REFERENCES Editorial(ID),
    FOREIGN KEY (ID_T_Obras) REFERENCES T_Obras(ID)
);

CREATE TABLE Ejemplares (
    N_de_Ejemplar INT, -- [cite: 126, 134]
    Año_Obras INT, -- 
    Cutter_Autores VARCHAR(20), -- 
    Codigo_CDD INT, -- 
    ID_Estado INT, -- 
    ID_Soporte INT, -- 
    PRIMARY KEY (N_de_Ejemplar, Año_Obras, Cutter_Autores, Codigo_CDD),
    FOREIGN KEY (Año_Obras, Cutter_Autores, Codigo_CDD) REFERENCES Obras(Año, Cutter_Autor, Codigo_CDD),
    FOREIGN KEY (ID_Estado) REFERENCES Estado(ID),
    FOREIGN KEY (ID_Soporte) REFERENCES T_soporte(ID)
);

CREATE TABLE Prestamos (
    N_de_control INT PRIMARY KEY, -- [cite: 124, 134]
    Fecha_de_prestamo VARCHAR(20), -- [cite: 124, 134]
    Año_del_prestamo INT, -- [cite: 134]
    Devolucion_de_prestamo VARCHAR(20), -- [cite: 124, 134]
    Tipo_de_prestamo VARCHAR(20) -- [cite: 124, 134]
);

-- 3. TABLAS DE RELACIONES DE ACCIÓN (Renueva y Cancela)

CREATE TABLE Renueva (
    N_control INT, -- [cite: 129]
    Username VARCHAR(20), -- [cite: 129]
    F_Ren VARCHAR(20), -- [cite: 129, 133]
    PRIMARY KEY (N_control, Username),
    FOREIGN KEY (N_control) REFERENCES Prestamos(N_de_control),
    FOREIGN KEY (Username) REFERENCES Admin(Username)
);

CREATE TABLE Cancela (
    N_control INT, -- [cite: 131]
    Username VARCHAR(20), -- [cite: 131]
    F_Dev VARCHAR(20), -- [cite: 131, 133]
    PRIMARY KEY (N_control, Username),
    FOREIGN KEY (N_control) REFERENCES Prestamos(N_de_control),
    FOREIGN KEY (Username) REFERENCES Admin(Username)
);