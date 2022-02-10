<!--loading note-->
<div id=loading>
  DETECTING PROBLEMS... please wait...
</div>

<style>
  #loading{
    background:yellow;
    padding:0.5em;
    transition:all 0.2s;
    margin-bottom:1em;
  }
</style>

<script>
  window.addEventListener('load',function(){
    document.querySelector('#loading').style.display='none';
  });
</script>
