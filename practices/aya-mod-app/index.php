<?php
  require_once("./config.php");
  
  $sql = "SELECT * FROM money";
  $stmt = mysqli_prepare($conn, $sql);
  
  
  if (!$stmt) {
    die("error ".mysqli_error($conn));
  }
  mysqli_stmt_execute($stmt);
  
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  
  if ($row) {
    $money = $row['money'];
  } else {
    $money = 0.00;
    $sqlInsert = "INSERT INTO money (money) VALUES (?);";
    $stmtInsert = mysqli_prepare($conn, $sqlInsert);
    mysqli_stmt_bind_param($stmtInsert, "d", $money);
    mysqli_stmt_execute($stmtInsert);
  }
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['money'])) {
    $newmoney = (float) $_POST['money'];
    $updateSQL = "UPDATE money SET money = ?";
    $updateStmt = mysqli_prepare($conn, $updateSQL);
    mysqli_stmt_bind_param($updateStmt, "d", $newmoney);
    mysqli_stmt_execute($updateStmt);
    exit;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paymaya mod App</title>
    <link rel="stylesheet" href="./style.css"/>
</head>
<body>
  <div class="container">
    <div class="header">
      <div>P</div>
      <div class="header-right">
        <p>M</p>
        <p>N</p>
      </div>
    </div>
    <div class="tab">
      <button class="tab-btn active">Wallet</button>
      <button class="tab-btn">Savings</button>
      <button class="tab-btn">Credit</button>
      <button class="tab-btn">Loans</button>
      <button class="tab-btn">Cards</button>
    </div>

    <div class="money-con">
      <div class="money-div">
        <div class="money-wrap">
          <div class="money">
            <div class="money-num">
              <p>₱</p>
              <p id="money"></p>
            </div>
            <p>E</p>
          </div>
          <p id="text-1">Wallet balance <span id="text-cashin">Auto cash in</span></p>
        </div>
        <div class="money-button-div">
          <button class="money-button">
            Cash In
          </button>
          <button class="money-button">
            Send
          </button>
        </div>
      </div>
      <div class="money-text">
        <div>
          <p style="font-weight: 600;">Easy credit</p>
          <span style="font-size: 0.7rem; ">Borrow up to 30k</span>
        </div>
        <div>
          <p style="font-size: 1rem;">Get it now</p>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  let money = <?php echo $money;?>;
  
  function MoneyPost() {
    money += 0.10;
    document.getElementById("money").textContent = money.toFixed(2);
    
    fetch('index.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'money=' + money.toFixed(2)
    })
  }
  setInterval(MoneyPost, 100);
</script>
</html>