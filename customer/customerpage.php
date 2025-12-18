<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Page</title>

    <style>
        /* ===== GLOBAL ===== */
        html { scroll-behavior: smooth; }
        body {
            margin: 0; font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f1ec; color: #333;
        }

        /* ===== HEADER ===== */
        header img {
            width: 100%; display: block;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* ===== NAVIGATION 1 (TOP) ===== */
        #navigation1 {
            background-color: #3b2f2f;
            position: sticky; top: 0; z-index: 1000;
            display: flex; justify-content: center; align-items: center;
            height: 60px;
        }
        #navigation1 ul {
            list-style: none; margin: 0; padding: 0; display: flex; justify-content: center;
        }
        #navigation1 li { margin: 0 20px; }
        #navigation1 a {
            color: white; text-decoration: none; padding: 15px 0; display: block;
        }
        #navigation1 a:hover { color: #ffcc66; }

        /* ===== LOGIN BUTTON STYLE ===== */
        .login-btn {
            position: absolute; right: 30px;
            background-color: #ffcc66; color: #3b2f2f !important;
            padding: 10px 25px !important; border-radius: 25px;
            font-weight: bold; text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            transition: transform 0.2s ease, background-color 0.2s;
        }
        .login-btn:hover {
            background-color: #ffdb99; transform: scale(1.05);
        }

        /* ===== WELCOME MESSAGE STYLE ===== */
        .welcome-msg {
            position: absolute; right: 30px;
            color: #ffcc66;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
        }

        /* ===== NAVIGATION 2 (MENU CATEGORIES) ===== */
        #navigation2 {
            background-color: #E1D9D1; border-bottom: 2px solid #c8bfb6;
            position: sticky; top: 60px; z-index: 999;
        }
        #navigation2 ul {
            list-style: none; margin: 0; padding: 0; display: flex; justify-content: center;
        }
        #navigation2 li { position: relative; margin: 0 15px; }
        #navigation2 a {
            text-decoration: none; color: #333; padding: 12px 0; display: block; font-weight: bold;
        }
        #navigation2 a:hover { color: #8b0000; }

        /* ===== DROPDOWN ===== */
        .dropdown-content {
            display: none; position: absolute; background-color: #E1D9D1;
            min-width: 180px; z-index: 1; border-radius: 5px;
            overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        #navigation2 .dropdown-content a {
			display: block;
			color: #333;
			/* Top: 12px, Right: 16px, Bottom: 12px, Left: 40px */
			padding: 12px 16px 12px 20px; 
		}
		#navigation2 .dropdown-content a:hover {
			color: #8b0000;
			background-color: #C7B6A6;
		}
        .dropdown:hover .dropdown-content { display: block; }

        /* ===== CONTENT SECTIONS ===== */
        section { margin: 30px auto; text-align: center; scroll-margin-top: 100px; }
        #orderNow {
            text-align: center; color: #8b0000; margin: 40px 0 20px;
            background-color: #D4CEBE; padding: 10px 0;
        }
        section h2 { color: #3b2f2f; }

        /* ===== IMAGE HOVER EFFECTS ===== */
        section a { position: relative; display: inline-block; }
        section img { display: block; transition: transform 0.3s ease, filter 0.3s ease; }
        section img:hover { transform: scale(1.05); filter: brightness(80%); }
        .hoverText {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            color: white; font-size: 24px; background: rgba(0,0,0,0.6);
            padding: 6px 14px; border-radius: 6px; opacity: 0;
            transition: opacity 0.3s ease; pointer-events: none;
        }
        section a:hover .hoverText { opacity: 1; }

        /* ===== ALA CARTE SPECIFIC ===== */
        #ala article { display: inline-block; margin: 10px; }

        /* ===== FOOTER ===== */
        footer {
            background-color: #3b2f2f; color: white; text-align: center;
            padding: 40px 20px; margin-top: 60px;
        }
        footer h3 { color: #ffcc66; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        footer p { margin: 8px 0; font-size: 14px; }
        footer hr { border: 0; height: 1px; background: #5c4b4b; width: 80%; margin: 20px auto; }
        footer a.back-to-top {
            display: inline-block; margin-top: 20px; padding: 10px 25px;
            background-color: #5c4b4b; color: #ffcc66; text-decoration: none;
            border-radius: 20px; transition: background 0.3s;
        }
        footer a.back-to-top:hover { background-color: #ffcc66; color: #3b2f2f; }
    </style>
</head>

<body>

    <header id="top">
        <img src="menuPics/Slide1.jpeg" alt="SUP TULANG ZZ" height="650px">
    </header>
    
    <nav id="navigation1">
        <ul>
            <li><a href="#top">Home</a></li>

            <?php if (isset($_SESSION['user_name'])): ?>
                <li><a href="cart.php">My Cart</a></li>
                <li><a href="orderStatus.php">Order Status</a></li>
                <li><a href="profile.php">My Profile</a></li>
                
                <li>
                    <a href="logout.php" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
                </li>
            <?php endif; ?>
        </ul>

        <?php if (isset($_SESSION['user_name'])): ?>
            <span class="welcome-msg">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <?php else: ?>
            <a href="login.php" class="login-btn">Log In</a>
        <?php endif; ?>
    </nav>
    
    <nav id="navigation2">
        <ul>
            <li><a href="#information">Info</a></li>
            <li class="dropdown">
                <a href="#signatureDish">Menu ▾</a>
                <div class="dropdown-content">
                    <a href="#signatureDish">Signature Dish</a>
                    <a href="#breakfast">Breakfast Set</a>
                    <a href="#lunch">Lunch Set</a>
                    <a href="#rotiCanai">Roti Canai</a>
                    <a href="#ikan">Ikan</a>
                    <a href="#ala">Ala-Carte</a>
                    <a href="#westernFood">Western Food</a>
                    <a href="#gorengGoreng">Goreng-Goreng</a>
                    <a href="#drinks">Drinks</a>
                </div>
            </li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
    
    <section id="information">
        <h2>Information</h2>
        <video width="600" height="350" controls>
            <source src="videozz.mp4" type="video/mp4">
            Your browser does not support this video.
        </video>
    </section>
    
    <section id="orderNow">
        <hr>
            <h1>MENU</h1>
        <hr>
    </section>
    
    <section id="signatureDish">
        <h2>Signature Dish</h2>
        <a href="signatureDishOrder.php">
            <img src="menuPics/Slide3.jpeg">
            <span class="hoverText">Signature Dish</span>
        </a>
    </section>
    
    <section id="breakfast">
        <h2>Breakfast Set</h2>
        <a href="breakfastOrder.php">
            <img src="menuPics/Slide4.jpeg">
            <span class="hoverText">Breakfast Set</span>
        </a>
    </section>
    
    <section id="lunch">
        <h2>Lunch Set</h2>
        <a href="lunchOrder.php">
            <img src="menuPics/Slide6.jpeg">
            <span class="hoverText">Lunch Set</span>
        </a>
    </section>
    
    <section id="rotiCanai">
        <h2>Roti Canai</h2>
        <a href="rotiCanaiOrder.php">
            <img src="menuPics/Slide5.jpeg">
            <span class="hoverText">Roti Canai</span>
        </a>
    </section>
    
    <section id="ikan">
        <h2>Menu Ikan</h2>
        <a href="ikanOrder.php">
            <img src="menuPics/Slide7.jpeg">
            <span class="hoverText">Menu Ikan</span>
        </a>
    </section>
    
    <section id="ala">
        <h2>Ala-Carte Menu</h2>
        <article>
            <a href="alaOrder1.php">
                <img src="menuPics/Slide8.jpeg">
                <span class="hoverText">Ala-Carte Menu 1</span>
            </a>
        </article>
        <article>
            <a href="alaOrder2.php">
                <img src="menuPics/Slide9.jpeg">
                <span class="hoverText">Ala-Carte Menu 2</span>
            </a>
        </article>
    </section>
    
    <section id="westernFood">
        <h2>Western Food</h2>
        <a href="westernFoodOrder.php">
            <img src="menuPics/Slide10.jpeg">
            <span class="hoverText">Western Food</span>
        </a>
    </section>
    
    <section id="gorengGoreng">
        <h2>Goreng-Goreng</h2>
        <a href="gorengGorengOrder.php">
            <img src="menuPics/Slide11.jpeg">
            <span class="hoverText">Goreng-Goreng</span>
        </a>
    </section>
    
    <section id="drinks">
        <h2>Drinks</h2>
        <a href="drinksOrder.php">
            <img src="menuPics/Slide12.jpeg">
            <span class="hoverText">Drinks</span>
        </a>
    </section>
    
    <footer id="contact">
        <h3>Contact Us</h3>
        <p>Email: contact@suptulangzz.com</p>
        <p>Phone: +60 12-345 6789</p>
        <p>Location: 123, Jalan Sup Tulang, 75450 Melaka</p>
        <hr>
        <p>© 2025 SUP TULANG ZZ</p>
        <a href="#top" class="back-to-top">Back To Top</a>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const links = document.querySelectorAll('a[href^="#"]');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === 'logout.php') return;

                    e.preventDefault(); 

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        if (targetId === '#top' || targetId === '#home') {
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center',
                                inline: 'nearest'
                            });
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>