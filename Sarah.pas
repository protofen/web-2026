PROGRAM SarahRevere(INPUT, OUTPUT);
USES
  DOS;
VAR
  QueryString, Answer: STRING;
  PosLetter: INTEGER;

BEGIN
  Answer := '0';
  QueryString := GetEnv('QUERY_STRING');
  PosLetter := Pos('lanterns=', QueryString);
  IF PosLetter > 0
  THEN
    BEGIN
      Delete(QueryString, 1, PosLetter + 9);
      Answer := QueryString
    END;
  WRITELN('Content-Type: text/plain');
  WRITELN;
  IF Answer = '1'
  THEN
    WRITELN('The British are coming by sea')
  ELSE
    IF Answer = '2'
    THEN
      WRITELN('The British are coming by sea')
    ELSE
      WRITELN('Sarah didn`t say')
END.