import qrcode

def creer_qrcode(lien, fichier_sortie="woodemo.png"):
    # Générer le QR code à partir du lien
    qr = qrcode.QRCode(
        version=1,  # Contrôle la taille du QR code (1 = petit, 40 = grand)
        error_correction=qrcode.constants.ERROR_CORRECT_L,  # Niveau de correction d'erreur
        box_size=10,  # Taille de chaque case du QR code
        border=4  # Largeur de la bordure (minimum 4)
    )
    qr.add_data(lien)
    qr.make(fit=True)

    # Créer l'image du QR code
    image = qr.make_image(fill_color="red", back_color="black")
    
    # Sauvegarder le QR code dans un fichier image
    image.save(fichier_sortie)
    print(f"QR code enregistré sous le nom : {fichier_sortie}")

if __name__ == "__main__":
    # Lien pour lequel tu veux créer un QR code
    lien = input("Entrez le lien pour lequel vous voulez générer un QR code : ")
    creer_qrcode(lien)
