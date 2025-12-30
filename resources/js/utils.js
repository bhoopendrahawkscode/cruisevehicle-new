function setGeneralQuerySearch(thisArg) {
    const currentRoute = thisArg.$route;
    const currentQuery = { ...currentRoute.query };
    if(thisArg.page != '' && thisArg.page != undefined && thisArg.page != 1){
        currentQuery.page = thisArg.page;
    }
    if(thisArg.sortBy != '' && thisArg.sortBy != undefined){
        currentQuery.sortBy = thisArg.sortBy;
    }
    if(thisArg.orderBy != '' && thisArg.orderBy != undefined){
        currentQuery.orderBy = thisArg.orderBy;
    }
    if(thisArg.keyword != '' && thisArg.keyword != undefined){
        currentQuery.keyword = thisArg.keyword;
    }
    thisArg.$router.push({ path: currentRoute.path, query: currentQuery });
}
function getPageLoadGeneralSearch(thisArg) {
    if( thisArg.$route.query.page != '' && thisArg.$route.query.page != undefined){
        thisArg.page =     thisArg.$route.query.page;
    }
    if(thisArg.$route.query.sortBy != '' && thisArg.$route.query.sortBy != undefined){
        thisArg.sortBy =     thisArg.$route.query.sortBy;
    }
    if(thisArg.$route.query.orderBy != '' && thisArg.$route.query.orderBy != undefined){
        thisArg.orderBy =     thisArg.$route.query.orderBy;
    }
    if(thisArg.$route.query.keyword != '' && thisArg.$route.query.keyword != undefined){
        thisArg.keyword =     thisArg.$route.query.keyword;
        thisArg.keywordText =     thisArg.keyword;
    }
    resetGeneralSorting(thisArg);
}
function setGeneralSorting(thisArg,sortBy,orderBy){
    thisArg.sortBy = sortBy;
    thisArg.orderBy = orderBy;
    const currentRoute = thisArg.$route;
    const currentQuery = { ...currentRoute.query };
    currentQuery.sortBy = sortBy;
    currentQuery.orderBy = orderBy;
    thisArg.$router.push({ path: currentRoute.path, query: currentQuery });
    resetGeneralSorting(thisArg);
}
function setGeneralSearch(thisArg,type){
    thisArg.page = 1;
    thisArg.sortBy = "";
    thisArg.orderBy = "";
    if(type == 'reset'){
        thisArg.keyword = '';
        thisArg.keywordText = '';
    }else{
        thisArg.keyword =  thisArg.keywordText;
    }
    const keys = Object.keys(thisArg.fields);
    keys.forEach((key) => {
        thisArg.fields[key] = 'fa-sort';
    });
    const currentRoute = thisArg.$route;
    thisArg.$router.push({ path: currentRoute.path, query: {} });
}
function resetGeneralSorting(thisArg) {
    const keys = Object.keys(thisArg.fields);
    keys.forEach((key) => {
        thisArg.fields[key] = 'fa-sort';
    });
    if(thisArg.orderBy == 'asc' ){
        thisArg.fields[thisArg.sortBy] = 'fa-sort-down';
    }else if(thisArg.orderBy == 'desc' ){
        thisArg.fields[thisArg.sortBy] = 'fa-sort-up';
    }else{
        thisArg.fields[thisArg.sortBy] = 'fa-sort';
    }
}
function setGeneralPaging(thisArg,page){
    const currentRoute = thisArg.$route;
    const currentQuery = { ...currentRoute.query };
    currentQuery.page = page;
    thisArg.$router.push({ path: currentRoute.path, query: currentQuery });
    thisArg.page = page
}
const CommonMethods = {
    methods: {
        showExcerpt(text, maxLength) {
            if(text.length > maxLength){
                return text.substring(0, maxLength) + "...";
            }else{
                return text.substring(0, maxLength);
            }
        },
        convertUTCInClientTz(utc) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const originalDate = new Date(utc);
            const offset = originalDate.getTimezoneOffset(); // Get client timezone offset in minutes

            // Convert the original time to the client's timezone
            const convertedDate = new Date(originalDate.getTime() - (offset * 60 * 1000));

            // Format the converted time as desired (e.g., to display)
            const year = convertedDate.getFullYear();
            const monthName = monthNames[convertedDate.getMonth()];
            const day = String(convertedDate.getDate()).padStart(2, '0');
            return `${day} ${monthName} ${year}`;
        },
        ucFirst(str) {
            if (!str || str.length === 0) {
                return str;
            }
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    }
};

export { setGeneralQuerySearch,getPageLoadGeneralSearch,setGeneralSorting,setGeneralSearch,resetGeneralSorting,setGeneralPaging,CommonMethods };
