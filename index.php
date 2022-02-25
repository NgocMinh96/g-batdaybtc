<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAME BẮT ĐÁY BTC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin: 0;
            background-image: url('images/bg.jpg');
        }
    </style>
</head>

<body>
    <div class="fixed w-full p-3 flex justify-between">
        <div>
            <audio loop id="musicEl">
                <source src="tinhtam.mp3" type="audio/mpeg">
            </audio>
            <div class="text-2xl font-bold text-red-500">
                <span>Best Score: </span>
                <span id="bestScoreEl">0</span>
            </div>
            <div class="text-5xl font-bold text-amber-400">
                <span class="">Score: </span>
                <span id="scoreEl">0</span>
            </div>
        </div>

        <div class="font-bold text-pink-500 justify-self-end">
            Donate MOMO: 0936454609
        </div>
    </div>
    <div id="modalEl" class="fixed inset-0 flex items-center justify-center">
        <div class="bg-orange-100 max-w-md w-full p-5 text-center rounded-md">
            <span class="text-amber-600 text-2xl font-bold">BẮT ĐƯỢC
                <span id="endScoreEl" class="text-4xl">0</span>
                BITCOIN
            </span>
            <div>
                <button id="startGameBtn" class="bg-amber-700 hover:bg-amber-800 text-white font-bold px-5 py-2 rounded-md mt-10">BẮT
                    ĐÁY</button>
            </div>
        </div>
    </div>
    <canvas></canvas>
</body>

</html>

<script>
    function randomIntFromRange(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min)
    }

    const startGameBtn = document.querySelector('#startGameBtn')
    const modalEl = document.querySelector('#modalEl')
    const scoreEl = document.querySelector('#scoreEl')
    const bestScoreEl = document.querySelector('#bestScoreEl')
    const endScoreEl = document.querySelector('#endScoreEl')
    const musicEl = document.querySelector('#musicEl')
    const playerImage = 'images/hand.png'
    const coinImage = 'images/btc.png'

    const canvas = document.querySelector('canvas')
    const c = canvas.getContext('2d')

    canvas.width = innerWidth
    canvas.height = innerHeight

    const mouse = {
        x: innerWidth / 2,
        y: innerHeight / 2
    }

    addEventListener('mousemove', (event) => {
        mouse.x = event.clientX
        mouse.y = event.clientY
    })

    class Player {
        constructor(x, y, image) {
            this.position = {
                x,
                y
            }
            this.image = image
            this.width = image.width
            this.height = image.height
        }
        draw() {
            c.drawImage(this.image, this.position.x, this.position.y);
        }
        update() {
            this.draw()
        }
    }

    const gravity = 0.01
    const friction = 0
    class Coin {
        constructor(x, y, image, dy) {
            this.x = x
            this.y = y
            this.dy = dy
            this.image = image
            this.width = image.width
            this.height = image.height
        }
        draw() {
            c.drawImage(this.image, this.x, this.y);
        }
        update() {
            if (this.y + this.height > canvas.height) {
                this.dy = -this.dy * friction
            } else {
                this.dy += gravity
                if (this.dy > 6) this.dy -= 1
            }
            this.y += this.dy
            this.draw()
        }
    }

    function createImage(path) {
        let img = new Image()
        img.src = path
        return img
    }

    var player = new Player(undefined, undefined, createImage(playerImage))
    var coinArray = []

    function init() {
        player = new Player(undefined, undefined, createImage(playerImage))
        coinArray = []
    }

    function spwanBall() {
        for (let i = 0; i < 10000; i++) {
            let x = randomIntFromRange(0, canvas.width - 40)
            let y = randomIntFromRange(0, -1000000)
            coinArray.push(new Coin(x, y, createImage(coinImage), 0.5))
        }
    }

    var score = 0
    var bestScore = 0
    let animationId

    function animate() {
        animationId = requestAnimationFrame(animate)
        c.clearRect(0, 0, canvas.width, canvas.height)
        player.update()
        player.position.x = mouse.x - (player.width / 2)
        player.position.y = mouse.y - (player.height / 2)
        coinArray.forEach((coin, index) => {
            coin.update()
            let playerx = player.position.x
            let playery = player.position.y
            let coinx = coinArray[index].x
            let coiny = coinArray[index].y
            if (coinx > playerx && coinx < (playerx + player.width) && coiny > playery && coiny < (playery + player.height)) {
                coinArray.splice(index, 1)
                score += 1
                scoreEl.innerHTML = score
            }
            if (coiny >= canvas.height - 45) {
                coinArray.splice(index, 1)
                scoreEl.innerHTML = score
                endScoreEl.innerHTML = score
                if (score > bestScore) {
                    bestScore = score
                    bestScoreEl.innerHTML = bestScore
                }
                cancelAnimationFrame(animationId)
                modalEl.style.display = 'flex'
            }
        })
    }

    startGameBtn.addEventListener('click', () => {
        init()
        animate()
        spwanBall()
        modalEl.style.display = 'none'
        score = 0
        scoreEl.innerHTML = 0
        //turn on music
        musicEl.volume = 0.2;
        musicEl.play()
    })
</script>