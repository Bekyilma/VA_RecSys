#!/usr/bin/env python3
# coding: utf-8

"""
Fusion-based RecSys engine that uses limited (top_k=n) rankings.

Authors:
- Bereket A. Yilma <name.surname@uni.lu>
- Luis A. Leiva <name.surname@uni.lu>
"""

from fusion_engine import FusionEngine

class FusionEnginePartial(FusionEngine):
    """
    Implement a combined retrieval engine using late fusion.
    """

    def retrieval(self, preferences, n=9, top_k=9):
        return super().retrieval(preferences, n=n, top_k=top_k)


if __name__ == "__main__":
    from lda_engine import LDAEngine
    from bert_engine import BertEngine

    lda = LDAEngine()
    bert = BertEngine()

    preferences = {
      "000-02Q1-0000":1,
      "000-04H4-0000":1,
      "000-02W4-0000":1,
      "000-02ND-0000":1,
      "000-03N1-0000":5,
      "000-019R-0000":1,
      "000-03TQ-0000":1,
      "000-019X-0000":1,
      "000-02OU-0000":1
    }

    eng = FusionEnginePartial(models=(lda, bert), model_weights=(0.50, 0.50))
    eng.retrieval(preferences)
