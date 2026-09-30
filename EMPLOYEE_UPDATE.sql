-- create updated_employee table

CREATE TABLE updated_employee (
    EmpID NVARCHAR(50),
    Employee NVARCHAR(255),
    Dept NVARCHAR(100),
    DeptName NVARCHAR(255),
    DeptCode NVARCHAR(255)
);
GO


-- import txt file (convert xlsx to txt tab delimited file type)

BULK INSERT updated_employee
FROM 'C:\Temp\PAR_Open_Records.txt'
WITH (
    FIRSTROW = 2,
    FIELDTERMINATOR = '\t',
    ROWTERMINATOR = '\n',
    CODEPAGE = '65001',
    TABLOCK
);
GO


-- update Employee Name to remove double quotes

UPDATE updated_employee
SET Employee = REPLACE(Employee, '"', '');
GO


-- create column for accountabilityHeaders

ALTER TABLE accountabilityHeaders
ADD dept_old NVARCHAR(255) NULL,
    emp_name_old NVARCHAR(255) NULL;
GO


-- copy dept and emp_name values to old tables

UPDATE accountabilityHeaders
SET dept_old = dept,
    emp_name_old = emp_name;
GO


-- update emp_name amd dept from updated_employee table

UPDATE ah
SET 
    ah.emp_name = ue.Employee,
    ah.dept = ue.DeptName
FROM accountabilityHeaders ah
INNER JOIN updated_employee ue 
    ON ah.employee_id = ue.EmpID;
GO





-- for getting the dept values from SyncHRIS to parv2

SELECT ah.employee_id,
       emp.FullName,
       emp.DeptDesc
FROM (
    SELECT DISTINCT employee_id
    FROM parv2_db_082725.dbo.accountabilityHeaders
    WHERE dept IS NULL
      AND employee_id LIKE 'PMC%'
) ah
OUTER APPLY (
    SELECT TOP 1 e.FullName, d.DeptDesc
    FROM SyncHRIS.dbo.viewHREmpMaster e
    LEFT JOIN SyncHRIS.dbo.HRDepartment d ON d.DeptID = e.DeptID
    WHERE e.EmpID = ah.employee_id
    ORDER BY e.seqid DESC
) emp;
GO


-- add the lost records from SyncHRIS to updated_employee table

INSERT INTO parv2_db_082725.dbo.updated_employee (EmpID, Employee, DeptName)
SELECT ah.employee_id,
       emp.FullName,
       emp.DeptDesc
FROM (
    SELECT DISTINCT employee_id
    FROM parv2_db_082725.dbo.accountabilityHeaders
    WHERE dept IS NULL
      AND employee_id LIKE 'PMC%'
) ah
OUTER APPLY (
    SELECT TOP 1 e.FullName, d.DeptDesc
    FROM SyncHRIS.dbo.viewHREmpMaster e
    LEFT JOIN SyncHRIS.dbo.HRDepartment d ON d.DeptID = e.DeptID
    WHERE e.EmpID = ah.employee_id
    ORDER BY e.seqid DESC
) emp;
GO


-- run the update emp_name amd dept from updated_employee table again

UPDATE ah
SET 
    ah.emp_name = ue.Employee,
    ah.dept = ue.DeptName
FROM accountabilityHeaders ah
INNER JOIN updated_employee ue 
    ON ah.employee_id = ue.EmpID;
GO

