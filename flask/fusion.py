#!/usr/bin/env python3
# coding: utf-8

"""
API server for fusion-based RecSys engines.

Authors:
- Bereket A. Yilma <name.surname@uni.lu>
- Luis A. Leiva <name.surname@uni.lu>
"""

import sys
from flask import Flask, request, jsonify
from waitress import serve
from fusion_engine import FusionEngine
#from fusion_engine_total import FusionEngineTotal as FusionEngine
#from fusion_engine_partial import FusionEnginePartial as FusionEngine
from lda_engine import LDAEngine
from bert_engine import BertEngine
from resnet_engine import ResNetEngine


lda = LDAEngine()
bert = BertEngine()
resnet = ResNetEngine()

# CLI argument to choose fusion model type.
fusion_id = sys.argv[1] if len(sys.argv) > 1 else "lda_bert"

# Specify port for fusion engines.
if fusion_id == "lda_bert":
    port = 10503
    eng = FusionEngine(models=(lda, bert), model_weights=(0.50, 0.50))
elif fusion_id == "lda25_resnet75":
    port = 10505
    eng = FusionEngine(models=(lda, resnet), model_weights=(0.25, 0.75))
elif fusion_id == "lda50_resnet50":
    port = 10506
    eng = FusionEngine(models=(lda, resnet), model_weights=(0.50, 0.50))
elif fusion_id == "lda75_resnet25":
    port = 10507
    eng = FusionEngine(models=(lda, resnet), model_weights=(0.75, 0.25))
elif fusion_id == "bert25_resnet75":
    port = 10508
    eng = FusionEngine(models=(bert, resnet), model_weights=(0.25, 0.75))
elif fusion_id == "bert50_resnet50":
    port = 10509
    eng = FusionEngine(models=(bert, resnet), model_weights=(0.50, 0.50))
elif fusion_id == "bert75_resnet25":
    port = 10510
    eng = FusionEngine(models=(bert, resnet), model_weights=(0.75, 0.25))
else:
    raise ValueError(f'Fusion "{fusion_id}" not understood. Try e.g. "lda25_resnet75" or "bert25_resnet75"')


app = Flask(fusion_id)

@app.route("/retrieval", methods=["POST"])
def retrieval():
    """
    preferences = {"000-03W3-0000": 5, "000-02UP-0000": 5, "000-03IY-0000": 5, "000-0344-0000": 5, "000-02XU-0000": 5,
                   "000-01DN-0000": 5, "000-04PW-0000": 5, "000-017I-0000": 5, "000-04S4-0000": 5}
    """
    preferences = request.json

    recommendations = eng.retrieval(preferences, n=9)
    return jsonify(recommendations)


# Use a production server instead of Flask's built-in one.
# app.run(debug=False, port=port)
serve(app, port=port, threads=10)
