// This swaps out some characters in the National Font to have less fancy decenders. 
// So far the numbers and the lowercase g needs to be swapped. 
//-M.Stack
function swapNational() {
    var str = document.getElementById("body").innerHTML; 
    var res = str.replace(//replacethiswithvars/g, "change to correct html coverted unicode");
    document.getElementById("body").innerHTML = res;
}
swapNational();
