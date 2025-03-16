{ pkgs ? import <nixpkgs> {} }:

pkgs.mkShell {
  buildInputs = with pkgs; [
    (pkgs.php82.buildEnv {
      extensions = (
        { enabled, all }:
        enabled
        ++ (with all; [
          redis
          curl
          gd
          imagick
          intl
          mbstring
          mysqli
          zip
        ])
      );
    })
    php82Packages.composer
    laravel
  ];
  shellHook = ''
    echo "PHP 8.2 активирован"
  '';
}
