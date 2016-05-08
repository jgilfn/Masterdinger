<canvas style=" pointer-events: none; position: absolute; top: 0; left: 0; height: 120px; width: 120px; "id="myChart-<?php echo $champion['id'];?>" width="120" height="120"></canvas>

<script>


var ctx1 = document.getElementById("myChart-<?php echo $champion['id'];?>").getContext("2d");

var gradient = ctx1.createLinearGradient(0,0,0,180);
gradient.addColorStop(0, '<?php echo getColor($champion['level']); ?>');
gradient.addColorStop(1, '<?php echo getColor2($champion['level']); ?>');

var data = {
labels: [
"Level points",
"Missing points",
],
datasets: [
{
data: [<?php echo $champion['championPointsSinceLastLevel'];?>, <?php echo $champion['championPointsUntilNextLevel'];?>],
backgroundColor: [
gradient,
"rgba(0,0,0,0)",
],
hoverBackgroundColor: [
gradient,
"rgba(33,33,33,0.5)"
],
borderWidth: 0
}]
};

var ctx = document.getElementById("myChart-<?php echo $champion['id'];?>");

  var myChart = new Chart(ctx, {
type: 'doughnut',
animation:{
    animateScale:false,
    animateRotate:false
},
data: data,
options: {
legend: {
  display:false
},
cutoutPercentage: 95,
animation : false,
rotation: 0.5 * Math.PI
}
});
</script>

<?php

 ?>
