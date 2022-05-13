<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

    <body>
    <input type='time' id='timerf' class='timerr' value=''>
    <input type='time' id='timert' class='timerr' value=''>
    <span id='total' class='total' ></span>
    <button onclick="cal()">Click me</button>

</body>

<script>
    function cal(){
         var t1 = document.getElementById("timert").value;
         var t2 = document.getElementById("timerf").value;
         /**Hour */
        var timeFromHr = new Date("01/01/2007 " + t1).getHours();
        var timeTohr = new Date("01/01/2007 " + t2).getHours();
        var HrDiff = Math.abs(parseInt(timeTohr) - parseInt(timeFromHr));
        console.log(HrDiff);

        /**Minutes */
        var timeFrommtn1 = new Date("01/01/2007 " + t1).getMinutes();
        var timeTomtn = new Date("01/01/2007 " + t2).getMinutes();
        var mnDiff = Math.abs(parseInt(timeTomtn) - parseInt(timeFrommtn1));
        console.log(mnDiff);
        // HrDiff = ("0" + HrDiff).slice(-2);
        // mnDiff = ("0" + mnDiff).slice(-2);
        document.getElementById("total").innerHTML = HrDiff+'Hours :'+mnDiff+' Minutes';
    }
</script>

    </html>


 
