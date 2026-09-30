-- NOTE: pa change db name lang sir, kani ako local (parv2_db_070326 and SyncHRIS) 


-- for copying dept values to dept_old
UPDATE accountabilityHeaders
SET dept_old = dept
WHERE document_date >= '2026-01-01'
GO



-- for getting the dept values from SyncHRIS to parv2 (VIEWING ONLY FOR TEST)
SELECT ah.employee_id,
       emp.FullName,
       emp.DeptDesc
FROM (
    SELECT DISTINCT employee_id
    FROM parv2_db_070326.dbo.accountabilityHeaders
    WHERE document_date >= '2026-01-01'
      AND employee_id LIKE 'PMC%'
) ah
OUTER APPLY (
    SELECT TOP 1 e.FullName, d.DeptDesc
    FROM SyncHRIS.dbo.viewHREmpMaster e
    LEFT JOIN SyncHRIS.dbo.HRDepartment d ON d.DeptID = e.DeptID
    WHERE e.EmpID = ah.employee_id AND d.GLCode='A'
    ORDER BY e.seqid DESC
) emp;
GO



-- clear updated_employee table for new data
DELETE FROM updated_employee;
GO




-- add the records from SyncHRIS to updated_employee table
INSERT INTO parv2_db_070326.dbo.updated_employee (EmpID, Employee, DeptName)
SELECT ah.employee_id,
       emp.FullName,
       emp.DeptDesc
FROM (
    SELECT DISTINCT employee_id
    FROM parv2_db_070326.dbo.accountabilityHeaders
    WHERE document_date >= '2026-01-01'
      AND employee_id LIKE 'PMC%'
) ah
OUTER APPLY (
    SELECT TOP 1 e.FullName, d.DeptDesc
    FROM SyncHRIS.dbo.viewHREmpMaster e
    LEFT JOIN SyncHRIS.dbo.HRDepartment d ON d.DeptID = e.DeptID
    WHERE e.EmpID = ah.employee_id AND d.GLCode='A'
    ORDER BY e.seqid DESC
) emp;
GO



-- run to update dept on accountabilityHeader from deptname in updated_employee table 
UPDATE ah
SET 
	ah.dept = ue.DeptName
FROM accountabilityHeaders ah
INNER JOIN updated_employee ue 
    ON ah.employee_id = ue.EmpID
WHERE ah.document_date >= '2026-01-01';
GO

