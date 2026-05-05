 PROGRAM FuncGetPostparametr(INPUT, OUTPUT);
USES 
  DOS;
VAR
  QueryString: STRING;
  
FUNCTION GetQueryStringParameter(Key: STRING): STRING;
VAR
  QueryString: STRING;
  PosName: INTEGER;
BEGIN {GetQueryStringParameter}
  QueryString := GetEnv('QUERY_STRING');
  PosName := Pos(Key, QueryString);
  IF PosName > 0 
  THEN
    BEGIN
      Delete(QueryString, 1, PosName + Length(Key));
      PosName := Pos('&', QueryString);
      IF PosName > 0 
      THEN 
        BEGIN
          GetQueryStringParameter := Copy(QueryString, 1, PosName - 1);
          Delete(QueryString, 1, PosName)
        END
    ELSE
      GetQueryStringParameter := QueryString
  END
END;
BEGIN {FuncGetPostparametr}
  
  WRITELN('First Name: ', GetQueryStringParameter('first_name'));
  WRITELN('Last Name: ', GetQueryStringParameter('last_name'));
  WRITELN('Age: ', GetQueryStringParameter('age'))
END. {FuncGetPostparametr}


