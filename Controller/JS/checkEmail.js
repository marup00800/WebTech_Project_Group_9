function checkEmail() {
  let email = document.getElementById("email").value;
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("emailCheckResponse").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "../Controller/HandleAjax.php", true);
  xhttp.setRequestHeader("content-type", "application/x-www-form-urlencoded");
  xhttp.send("email=" + email);
}