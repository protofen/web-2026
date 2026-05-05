PROGRAM PrintName(INPUT, OUTPUT);
USES
  DOS;
VAR
  QueryString, Name: STRING;
  PosName: INTEGER;
BEGIN
  QueryString := GetEnv('QUERY_STRING');
  PosName := Pos('name=', QueryString);
  IF PosName > 0
  THEN
    BEGIN
      Delete(QueryString, 1, PosName + 4);
      PosName := Pos('&', QueryString);
      IF PosName > 0
      THEN
        BEGIN
          Name := Copy(QueryString, 1, PosName - 1);
          Delete(QueryString, 1, PosName)
        END
    ELSE
      Name := QueryString
  END;
  
  WRITELN('Content-Type: text/plain');
  WRITELN;
  WRITELN('Hello dear, ', Name);
END.