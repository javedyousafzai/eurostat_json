# Eurostat JSON Pull Data for Asylum data analysis
These scripts pull data from eurostat API for asylum applications and decisions in the 32 (28 EU+EFTA) countries. Each dataset pull the data and save it in the csv format. 

Each dataset in Eurostat has it unique name we can use to pull data via API.Here is the list we are using to get the asylum data for EU (28 EU + 4 EFTA) countires;
1.		Asylum and first time asylum applicants by citizenship, age and sex Annual data (migr_asyappctza)
2.  Asylum and first time asylum applicants by citizenship, age and sex Monthly data (migr_asyappctzm)
3.  Asylum applicants considered to be unaccompanied minors by citizenship, age and sex Annual data (migr_asyunaa)
4.  First instance decisions on applications by citizenship, age and sex Quarterly data (rounded) 

API Dimensions: Data provided by the API is categorized in those six dimentstions. You can also specify in your API one of those dimensions as your input parameter to filter data or get specific results;
 -	"citizen",
 -	"sex",
 -	"unit",
 -	"age",
 -	"geo",
 -	"time" 
 
