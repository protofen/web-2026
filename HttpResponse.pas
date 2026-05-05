PROGRAM HttpResponse(INPUT, OUTPUT);
USES
  DOS;
BEGIN { HttpResponse }
  WRITELN('Content-Type: text/plain');
  WRITELN;  
  WRITELN(GetEnv('REQUEST_METHOD'));
  WRITELN(GetEnv('QUERY_STRING'))
END. { HttpResponse }