function checkPassword() {
  let password = document.getElementById("password").value;
  let length = password.length;
  if (length == 0) {
    document.getElementById("passwordCheckResponse").innerHTML = "";
  } else if (length < 8) {
    document.getElementById("passwordCheckResponse").innerHTML = "<span style='color:red;'>Password is too short. " + length + "/8 characters</span>";
  } else {
    document.getElementById("passwordCheckResponse").innerHTML = "<span style='color:green;'>Password length is good. " + length + " characters</span>";
  }
}