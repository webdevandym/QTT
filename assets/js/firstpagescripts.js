function O(i) {
  return typeof i == 'object' ? i : document.getElementById(i)
}

let paintCanvas = function() {

  var canvas = O('logo'),
    context = canvas.getContext('2d'),
    gradient = context.createLinearGradient(0, 0, 0, 89),
    image = new Image();

  image.src = './assets/image/ico-leaf.png'

  window.onload = function() {

    context.font = 'bold italic 70px "PT Sans"'
    context.textBaseline = 'top'

    gradient.addColorStop(0.00, 'white')
    gradient.addColorStop(0.33, 'rgba(75, 183, 10, 0.8)')
    gradient.addColorStop(.66, '#2ea04d')
    context.fillStyle = gradient
    context.fillText("QTT Panel", 0, 0)
    context.drawImage(image, 310, 0)
  }
}

$('.loginInput').on('blur', function() {
  $.get('./core/loginControler/loginChecker.php?q=' + encodeURIComponent($('.loginInput').val()), function(data) {
    $('#loginChecker').html(data)
  })
})

$(".loginInput").keypress(function(event) {
  if (event.which == 13 && $(this).val() != '') {
    event.preventDefault();
    checkAndLogin()
  }
});


$('.submitLogin').on('click', function() {
  checkAndLogin()
})


function checkAndLogin() {
  let login;

  if ((login = encodeURIComponent($('.loginInput').val())) != '') {

    $.ajax({
      method: 'GET',
      url: './core/loginControler/loginChecker.php?q=' + login + '&sendResult=true',
      success: (data) => {
        if (data != '') {
          $.get('index.php?user=' + login, function(data) {
            $('body').html(data)
          })
        } else {
          $('#errorHolder').html("<span class = 'error'>Please check your domain username</span>")
        }
      }
    })

  } else $('#errorHolder').html("<span class = 'error'>Please enter your domain username</span>");

}

paintCanvas()
