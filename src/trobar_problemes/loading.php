<!--loading note-->

<div id=loading>
  DETECTING PROBLEMS... please wait...
</div>

<script>
  window.addEventListener('load',function(){
    document.querySelector('#loading').style.display='none';
  });
</script>

<style>
  #loading{
    text-align:center;
    background:yellow;
    padding:0.5em;
    transition:all 0.2s;
  }
</style>
