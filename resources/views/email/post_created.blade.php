<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <title>Nova publicação</title>
</head>

<body style="padding: 0; margin: 0; background-color: #ffffff">
    <div
        style="
        margin: 0;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI,
          Helvetica Neue, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        color: #333333;
        background-color: #ffffff;
        margin: 0;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI,
          Helvetica Neue, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
      ">
        <table align="center" cellpadding="0" cellspacing="0"
            style="max-width: 685px; padding: 0px 20px; margin: 38px auto 0px auto">
            <tbody>
                <tr>
                    <td>
                        <h4
                            style="
                            font-size: 14px;
                  margin: 0;
                  margin-bottom: 10px;
                  text-align: center;
                ">
                            Nova publicação em <strong>"{{ $post->topic?->name ?? 'tópico não encontrado' }}"</strong>!
                        </h4>
                        <p
                            style="font-size:10px;margin: 0;
                  margin-bottom: 20px;
                  text-align: center;
                  font-weight: normal;">
                            Olá, {{ $user->name }}! Você está recebendo este e-mail pois há uma nova publicação no
                            tópico
                            <strong>"{{ $post->topic?->name ?? 'tópico não encontrado' }}"</strong>, o qual você está inscrito.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="border:1px solid #dedede;padding: 20px;border-radius: 12px">
                        <h4
                            style="
                            font-size: 16px;
                  margin: 0;
                  margin-bottom: 5px;
                  text-align: left;
                  font-weight: bold;
                ">
                            {{ $post->title }}
                        </h4>
                        <p
                            style="font-size:10px;margin: 0;
                            line-height: 1.5;
                            text-align: left;
              margin-bottom: 20px;">
                            Publicado por <strong>{{ $post->creator?->name ?? 'Autor não encontrado' }}</strong> em
                            <strong>{{ $post->created_at->format('d/m/Y H:i') }}</strong>
                        </p>
                        <p
                            style="font-size:14px;margin: 0;
                            line-height: 1.5;
                            text-align: justify;
              ">
                            {{ $post->content }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
