%{
#include<stdio.h>
#include<stdlib.h>
#include<string.h>

void appendMetaChar(char* );
void doc2FlagsReset();

char tmp1[500];
int refflag=0,fflag=0,doc2flag=0;

FILE *fp;

%}

ws	[ \t]*
num	[0-9]+
cite	"["{ws}{num}{ws}"]"
ref	"REFERENCES"|"BIBLIOGRAPHY"

%%

{cite}	{
		if(refflag)
		{
			if(fflag)
			{
				appendMetaChar(tmp1);
				fwrite(tmp1,sizeof(char),strlen(tmp1),fp);
			}
			else
				fflag=1;
			strcpy(tmp1," ");
		}
	}


{ref}	{
		if(doc2flag)
			fp=fopen("ref/refmtch2","w");
		else
			fp=fopen("ref/refmtch1","w");
		refflag=1;
	}

.|\n	{
		if(refflag)
			strcat(tmp1,yytext);
	}


%%

int main(int argc,char **argv)
{
	system("pdftotext files/in1.pdf");
	system("pdftotext files/in2.pdf");
	yyin=fopen("files/in1.txt","r");
	yylex();
	doc2FlagsReset();
	yyin=fopen("files/in2.txt","r");
	yylex();
	fclose(fp);
	return 0;
}

int yywrap()
{
	appendMetaChar(tmp1);
	fwrite(tmp1,sizeof(char),strlen(tmp1),fp);
	return 1;
}

void appendMetaChar(char* tmp1)
{
	int j=strlen(tmp1);
        tmp1[j++]=';';
        tmp1[j++]=';';
        tmp1[j]='\0';
}

void doc2FlagsReset()
{
	refflag=0;
	fflag=0;
	doc2flag=1;
}

