function checkUsername() {
  let name = document.getElementById("name").value;
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("nameCheckResponse").innerHTML = this.responseText;
    } else {
      document.getElementById("nameCheckResponse").innerHTML = this.status;
    }
  };
  xhttp.open("POST", "../Controller/HandleAjax.php", true);
  xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
  xhttp.send("name=" + name);
}
