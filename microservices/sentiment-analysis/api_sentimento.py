from flask import Flask, request, jsonify
from transformers import pipeline

app = Flask(__name__)

# Carrega o modelo Multilingue (funciona bem para PT-BR)
# Classifica de 1 a 5 estrelas
print("--- INICIANDO IA ALIEXPRESSO ---")
print("Carregando modelo BERT... (Isso pode demorar um pouco na primeira vez)")
sentiment_pipeline = pipeline("sentiment-analysis", model="nlptown/bert-base-multilingual-uncased-sentiment")
print("IA Pronta para receber avaliações!")

def classificar_sentimento(label):
    # O modelo retorna "1 star", "4 stars".
    stars = int(label.split()[0])
    
    if stars <= 2:
        return "Ruim"
    elif stars == 3:
        return "Neutro"
    else:
        return "Bom"

@app.route('/analisar', methods=['POST'])
def analisar():
    data = request.json
    comentario = data.get('comentario', '')

    if not comentario:
        return jsonify({'erro': 'Comentário vazio'}), 400

    # Limita a 512 caracteres para evitar erro no modelo BERT
    resultado = sentiment_pipeline(comentario[:512])[0]
    
    sentimento = classificar_sentimento(resultado['label'])
    score = round(resultado['score'], 4)
    estrelas_ia = int(resultado['label'].split()[0])

    return jsonify({
        'sentimento': sentimento,
        'score': score,
        'estrelas_ia': estrelas_ia
    })

if __name__ == '__main__':
    # Roda na porta 5000
    app.run(host='0.0.0.0', port=5000)