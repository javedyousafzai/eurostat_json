# Eurostat JSON Pull Data for Asylum data analysis
These scripts pull data from eurostat API for asylum applications and decisions in the 32 (28 EU+EFTA) countries. Each dataset pull the data and save it in the csv format. 

Each dataset in Eurostat has it unique name we can use to pull data via API
here is the list;

1.	Asylum and first time asylum applicants by citizenship, age and sex Annual aggregated data (rounded) (#migr_asyappctza)	 
2.	Asylum and first time asylum applicants by citizenship, age and sex Monthly data (rounded) (#migr_asyappctzm)
3.	Persons subject of asylum applications pending at the end of the month by citizenship, age and sex Monthly data (rounded) (#migr_asypenctzm)
4.	Asylum applications withdrawn by citizenship, age and sex Annual aggregated data (rounded) (#migr_asywitha)
5.	Asylum applications withdrawn by citizenship, age and sex Monthly data (rounded) (#migr_asywithm)	 
6.	Asylum applicants considered to be unaccompanied minors by citizenship, age and sex Annual data (rounded) (#migr_asyunaa)

API Dimensions: there are six dimentions you can specify in your API;
 1	"citizen",
 2	"sex",
 3	"unit",
 4	"age",
 5	"geo",
 6	"time" 
