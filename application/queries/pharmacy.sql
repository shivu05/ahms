-- Medicines Table
CREATE TABLE Medicines (
    MedicineID INT AUTO_INCREMENT PRIMARY KEY,
    MedicineName VARCHAR(255) NOT NULL,
    GenericName VARCHAR(255),
    Dosage VARCHAR(50),
    Form VARCHAR(50),
    Manufacturer VARCHAR(255),
    NDC VARCHAR(50),
    Description TEXT,
    ControlledSubstance BOOLEAN,
    RequiresPrescription BOOLEAN,
    StorageConditions TEXT,
    SideEffects TEXT,
    Interactions TEXT,
    UnitPrice DECIMAL(10, 2),
    ReorderLevel INT,
    Category VARCHAR(100),
    Image VARCHAR(255),
    DateAdded DATE,
    LastUpdated DATE
);

-- Batches Table
CREATE TABLE Batches (
    BatchID INT AUTO_INCREMENT PRIMARY KEY,
    MedicineID INT,
    BatchNumber VARCHAR(50) NOT NULL,
    ExpiryDate DATE,
    QuantityReceived INT,
    QuantityInStock INT,
    SupplierID INT,
    StorageLocation VARCHAR(255),
    DateReceived DATE,
    FOREIGN KEY (MedicineID) REFERENCES Medicines(MedicineID),
    FOREIGN KEY (SupplierID) REFERENCES Suppliers(SupplierID)
);

-- Suppliers Table
CREATE TABLE Suppliers (
    SupplierID INT AUTO_INCREMENT PRIMARY KEY,
    SupplierName VARCHAR(255) NOT NULL,
    ContactInformation VARCHAR(255)
);

-- Patients Table
CREATE TABLE Patients (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255),
    DateOfBirth DATE,
    Address VARCHAR(255),
    PhoneNumber VARCHAR(20),
    Email VARCHAR(255),
    MedicalHistory TEXT
);

-- Prescriptions Table
CREATE TABLE Prescriptions (
    PrescriptionID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT,
    DoctorName VARCHAR(255),
    PrescriptionDate DATE,
    Instructions TEXT,
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID)
);

-- PrescriptionItems Table
CREATE TABLE PrescriptionItems (
    ItemID INT AUTO_INCREMENT PRIMARY KEY,
    PrescriptionID INT,
    MedicineID INT,
    Dosage VARCHAR(50),
    Quantity INT,
    FOREIGN KEY (PrescriptionID) REFERENCES Prescriptions(PrescriptionID),
    FOREIGN KEY (MedicineID) REFERENCES Medicines(MedicineID)
);

-- Sales Table
CREATE TABLE Sales (
    SaleID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT,
    SaleDate DATETIME,
    TotalAmount DECIMAL(10, 2),
    FOREIGN KEY (PatientID) REFERENCES Patients(PatientID)
);

-- SaleItems Table
CREATE TABLE SaleItems (
    SaleItemID INT AUTO_INCREMENT PRIMARY KEY,
    SaleID INT,
    MedicineID INT,
    BatchID INT,
    Quantity INT,
    Price DECIMAL(10, 2),
    FOREIGN KEY (SaleID) REFERENCES Sales(SaleID),
    FOREIGN KEY (MedicineID) REFERENCES Medicines(MedicineID),
    FOREIGN KEY (BatchID) REFERENCES Batches(BatchID)
);

-- Users Table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Role VARCHAR(50) -- e.g., Pharmacist, Admin, Staff
);